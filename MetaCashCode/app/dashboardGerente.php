<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['id_empresa'])) {
    $_SESSION['id_empresa'] = 1;
}

$id_empresa = $_SESSION['id_empresa'];

// BUSCA TOTAIS DO BANCO DE DADOS (MySQL)
$total_receitas = 0;
$total_despesas = 0;
$saldo_total = 0;

try {
    $stmt_totais = $pdo->prepare("SELECT 
        SUM(CASE WHEN tipo_transacao = 'Receita' THEN valor_transacao ELSE 0 END) as receitas,
        SUM(CASE WHEN tipo_transacao = 'Despesa' THEN valor_transacao ELSE 0 END) as despesas
        FROM transacoes WHERE id_empresa = :empresa");
    $stmt_totais->execute([':empresa' => $id_empresa]);
    $resultado_totais = $stmt_totais->fetch();

    if ($resultado_totais) {
        $total_receitas = (float)$resultado_totais['receitas'];
        $total_despesas = (float)$resultado_totais['despesas'];
        $saldo_total = $total_receitas - $total_despesas;
    }
} catch (PDOException $e) {
    error_log("Erro ao buscar totais no dashboardGerente.php: " . $e->getMessage());
}

// LÓGICA PARA O GRÁFICO DE LINHA (Simplificado para o momento atual)
$labels_meses = [date('M')]; // Mostra o mês atual (ex: 'May')
$dados_receitas = [$total_receitas]; 
$dados_despesas = [$total_despesas]; 
$dados_lucro = [$saldo_total];

// 4. LÓGICA PARA O GRÁFICO DE PIZZA/ROSCA (Distribuição por Categorias)
$categorias_temp = [];

try {
    // Puxa a soma de valores agrupados pelo nome da categoria
    $stmt_cat = $pdo->prepare("
        SELECT c.nome_categoria, t.tipo_transacao, SUM(t.valor_transacao) as total
        FROM transacoes t
        LEFT JOIN categoria c ON t.id_categoria = c.id_categoria
        WHERE t.id_empresa = :empresa
        GROUP BY c.nome_categoria, t.tipo_transacao
    ");
    $stmt_cat->execute([':empresa' => $id_empresa]);
    $categorias_db = $stmt_cat->fetchAll();

    // Processa Receitas vs Despesas para o gráfico
    foreach ($categorias_db as $row) {
        $cat = $row['nome_categoria'] ?? 'Geral';
        $valor = (float)$row['total'];
        
        if ($row['tipo_transacao'] === 'Despesa') {
            $categorias_temp[$cat] = ($categorias_temp[$cat] ?? 0) - $valor;
        } else {
            $categorias_temp[$cat] = ($categorias_temp[$cat] ?? 0) + $valor;
        }
    }
} catch (PDOException $e) {
    error_log("Erro no gráfico de categorias: " . $e->getMessage());
}

// Se o banco estiver vazio, coloca um dado falso só para o gráfico não dar erro visual
if (empty($categorias_temp)) {
    $categorias_temp['Sem movimentação'] = 0;
}

// PREPARAÇÃO PARA O JAVASCRIPT DOS GRÁFICOS
$categorias_valores = array_values($categorias_temp);
$categorias_labels = array_keys($categorias_temp);

// 5. CARDS DO DASHBOARD
$lucro_mes = $total_receitas - $total_despesas; 

$cards = [
    'Lucro Mensal' => [
        'valor' => ($lucro_mes > 0 ? '+' : '') . number_format($lucro_mes, 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => $lucro_mes >= 0 ? 'text-teal-500' : 'text-rose-500'
    ],
    'Total de Receitas' => [
        'valor' => '+' . number_format($total_receitas, 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => 'text-teal-500'
    ],
    'Total de Despesas' => [
        'valor' => '-' . number_format($total_despesas, 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => 'text-rose-500'
    ]
];

// Mock de transações recentes, já que não estava em data.php
$transacoes = [];

// Mock de dados para o saldo total, pois a variável $dados não existe mais
$dados = [
    'saldo_total' => $saldo_total,
    'receitas_mes' => $total_receitas,
    'despesas_mes' => $total_despesas,
];

if (!function_exists('formatarMoeda')) {
    function formatarMoeda($valor) {
        return number_format($valor, 2, ',', '.');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MetaCash - Gestão</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">

<aside class="w-64 bg-[#0f172a] text-white p-4 flex flex-col fixed h-screen shrink-0 z-40">
    <div class="flex items-center gap-3 mb-10 px-2 pt-2">
        <img src="../assets/img/logo.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover" onerror="this.src='https://ui-avatars.com/api/?name=MC&background=2dd4bf&color=0f172a'">
        <div class="flex flex-col">
            <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
            <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Gestão Empresarial</span>
        </div>
    </div>

    <nav class="flex-1 space-y-2">
        <a href="dashboardGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#2dd4bf] text-white shadow-lg transition">
            <i class="fas fa-th-large w-5"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        <a href="transacoesGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
            <i class="fas fa-exchange-alt w-5"></i>
            <span class="font-medium">Transações</span>
        </a>
        <a href="gerenciaEquipe.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
            <i class="fas fa-users w-5"></i>
            <span class="font-medium">Equipe</span>
        </a>
        <a href="gerenciaPaginas.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
            <i class="fas fa-file-alt w-5"></i>
            <span class="font-medium">Gerenciar Páginas</span>
        </a>
        <a href="historico.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
            <i class="fas fa-history w-5"></i>
            <span class="font-medium">Histórico</span>
        </a>
        <a href="configuracao.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
            <i class="fas fa-cog w-5"></i>
            <span class="font-medium">Configurações</span>
        </a>
        <button onclick="toggleModal('modalRelatorio')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition border border-transparent hover:border-slate-700 text-left">
            <i class="fas fa-file-pdf w-5"></i>
            <span class="font-medium">Baixar Relatório</span>
        </button>
    </nav>

    <div class="mt-auto pt-6 border-t border-slate-800 space-y-4 pb-2">
        <a href="perfil.php" class="bg-[#1e3a5f]/40 p-3 rounded-2xl flex items-center gap-3 border border-slate-700/50 hover:bg-[#1e3a5f]/60 transition block group">
            <div class="w-10 h-10 bg-[#2dd4bf] rounded-full flex items-center justify-center text-[#0f172a] font-bold text-lg shrink-0 group-hover:scale-105 transition-transform">U</div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-sm font-bold truncate">Usuário</span>
                <span class="text-[10px] text-gray-400 truncate">usuario@exemplo.com</span>
            </div>
        </a>
        <a href="../auth/logout.php" class="flex items-center gap-3 px-4 py-2 text-gray-400 hover:text-white transition group">
            <i class="fas fa-sign-out-alt rotate-180 group-hover:text-red-400 transition-colors"></i>
            <span class="font-medium">Sair</span>
        </a>
    </div>
</aside>

    <main class="flex-1 p-8 ml-64 w-full">
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-[#0f172a] tracking-tight">Visão Geral Financeira</h1>
            <p class="text-lg text-[#334155] mt-2">Acompanhe o desempenho financeiro da sua empresa</p>
        </div>

        <section class="bg-[#0f1c30] rounded-2xl p-8 text-white mb-8 shadow-xl">
            <div class="flex items-center gap-2 text-slate-400 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V5a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs font-medium uppercase tracking-wider">Saldo Total</span>
            </div>

            <div class="text-4xl font-bold mb-8">
                R$ <?php echo number_format($dados['saldo_total'], 2, ',', '.'); ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white/5 border border-white/10 p-4 rounded-xl backdrop-blur-sm">
                    <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                        <span class="text-teal-400 font-bold">↗</span> Receitas
                    </div>
                    <div class="text-xl font-semibold text-white">
                        R$ <?php echo number_format($dados['receitas_mes'], 2, ',', '.'); ?>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 p-4 rounded-xl backdrop-blur-sm">
                    <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                        <span class="text-rose-400 font-bold">↘</span> Despesas
                    </div>
                    <div class="text-xl font-semibold text-white">
                        R$ <?php echo number_format($dados['despesas_mes'], 2, ',', '.'); ?>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-4 gap-4 mb-8">
            <?php foreach($cards as $titulo => $info): ?>
                <div class="bg-white p-4 rounded-xl border border-slate-200">
                    <div class="flex justify-between text-xs font-bold mb-2">
                        <span class="text-slate-500 uppercase"><?php echo $titulo; ?></span>
                        <span class="<?php echo $info['cor']; ?>"><?php echo $info['porcentagem']; ?></span>
                    </div>
                    <div class="text-2xl font-bold text-slate-800">R$ <?php echo $info['valor']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl border border-slate-200">
                <h3 class="font-bold mb-4 text-slate-800">Receitas vs Despesas</h3>
                <div class="h-64"><canvas id="graficoLinha"></canvas></div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-slate-200">
                <h3 class="font-bold mb-4 text-slate-800">Despesas por Categoria</h3>
                <div class="h-64"><canvas id="graficoPizza"></canvas></div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex justify-between mb-4">
                <h3 class="font-bold text-slate-800">Transações Recentes</h3>
                <a href="#" class="text-teal-500 text-sm font-medium">Ver todas</a>
            </div>
            <div class="space-y-4">
                <?php foreach($transacoes as $tr): ?>
                <div class="flex justify-between items-center py-2 border-b border-slate-50 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center <?php echo $tr['tipo'] == 'e' ? 'bg-teal-50 text-teal-500' : 'bg-slate-100 text-slate-500'; ?>">
                            <?php echo $tr['tipo'] == 'e' ? '↑' : '↓'; ?>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-700"><?php echo $tr['titulo']; ?></p>
                            <p class="text-xs text-slate-400"><?php echo $tr['cat']; ?> • <?php echo $tr['data']; ?></p>
                        </div>
                    </div>
                    <p class="font-bold <?php echo $tr['tipo'] == 'e' ? 'text-teal-500' : 'text-rose-500'; ?>">
                        <?php echo ($tr['tipo'] == 'e' ? '+' : '-') . formatarMoeda($tr['valor']); ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <script src="../assets/js/dashboardGerente.js"></script>
</body>
</html>
