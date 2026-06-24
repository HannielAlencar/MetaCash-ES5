<?php
require_once __DIR__ . '/../config.php';
// 1. SEGURANÇA E CONEXÃO
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

<<<<<<< Updated upstream
if (!isset($_SESSION['id_empresa'])) {
    // Se não tiver empresa logada, não carrega dados
    $id_empresa = 0; 
} else {
    $id_empresa = $_SESSION['id_empresa'];
}

// BUSCA TOTAIS DO BANCO DE DADOS (MySQL)
=======
// Trava de segurança: impede acesso se não estiver logado OU se não possuir o nível exigido
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['nivel_permissao']) || ($_SESSION['nivel_permissao'] !== 'Gerente' && $_SESSION['nivel_permissao'] !== 'Admin')) {
    header("Location: dashboardUsuario.php");
    exit();
}

$id_empresa = $_SESSION['id_empresa'];
$config_dashboard_db = 'null';

try {
    $stmt = $pdo->prepare("SELECT config_dashboard FROM configuracoes_paginas WHERE id_empresa = :id");
    $stmt->execute([':id' => $id_empresa]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['config_dashboard'])) {
        $config_dashboard_db = $row['config_dashboard'];
    }
} catch (PDOException $e) {}

>>>>>>> Stashed changes
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
    <link rel="stylesheet" href="../assets/css/dashboardGerente.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body class="flex">

<aside class="w-64 bg-slate-900 min-h-screen p-6 text-white flex flex-col">
    <div class="mb-10 flex items-center gap-3">
        <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center">
            <img src="../assets/img/logoCyano.png" alt="MetaCash" onerror="this.style.display='none';">
            <span class="text-white font-bold text-xl"></span>
        </div>
        <div>
            <div class="text-white font-bold text-lg leading-none">MetaCash</div>
            <div class="text-slate-500 text-[10px] uppercase tracking-wider">Gestão Empresarial</div>
        </div>
    </div>

    <nav class="space-y-2 flex-1 text-slate-400">
        <a href="dashboard.php" class="flex items-center gap-3 bg-teal-500/10 text-teal-400 p-3 rounded-xl font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            Dashboard
        </a>

        <a href="transacoes.php" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white p-3 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            Transações
        </a>

        <a href="equipe.php" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white p-3 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            Equipe
        </a>

        <a href="paginas.php" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white p-3 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            Gerenciar Páginas
        </a>

        <a href="historico.php" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white p-3 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Histórico
        </a>

        <a href="configuracoes.php" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white p-3 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            Configurações
        </a>
    </nav>

    <div class="mt-auto border-t border-slate-800 pt-6">
        <button class="w-full bg-slate-800 hover:bg-slate-700 text-white py-3 rounded-xl mb-6 transition text-sm font-medium">
            Baixar Relatório
        </button>

        <div class="bg-slate-800/50 p-4 rounded-2xl flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center font-bold text-white">U</div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold truncate">Usuário</p>
                <p class="text-[10px] text-slate-500 truncate">usuario@exemplo.com</p>
            </div>
        </div>
        
        <a href="../auth/logout.php" class="flex items-center gap-3 text-slate-400 hover:text-rose-400 transition text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
            Sair
        </a>
    </div>

    
</aside>

    <main class="flex-1 p-8 bg-gray-50">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Visão Geral Financeira</h1>
                <p class="text-slate-500 text-sm">Acompanhe o desempenho financeiro da sua empresa</p>
            </div>
            <button class="bg-gradient-to-r from-slate-800 to-teal-600 text-white px-4 py-2 rounded-lg transition font-medium flex items-center gap-2 shadow-lg hover:opacity-90">
                <span>+</span> Adicionar Transação
            </button>
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

<<<<<<< Updated upstream
    <script src="../assets/js/dashboardGerente.js"></script>
=======
    <!-- MODAL RELATÓRIO -->
    <div id="modalRelatorio" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-[60] p-4 backdrop-blur-sm">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl p-8">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-extrabold text-slate-800">Baixar Relatório</h3>
                <button onclick="toggleModal('modalRelatorio')" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="/MetaCashCode/Usuario/Transacoes.php/gerar_pdf.php" method="GET" target="_blank" class="space-y-6">
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Tipo de Transação</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="e" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Receita</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="s" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Despesa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="todos" checked class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Ambos</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Período</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="mensal" checked class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Mensal</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="anual" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Anual</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Mês</label>
                    <select name="mes" class="w-full p-4 rounded-2xl border border-slate-200 bg-white text-slate-700 font-medium appearance-none focus:outline-none focus:ring-2 focus:ring-meta-destaque/20 transition-all cursor-pointer">
                        <option value="1">Janeiro</option>
                        <option value="2">Fevereiro</option>
                        <option value="3">Março</option>
                        <option value="4">Abril</option>
                        <option value="5" selected>Maio</option>
                        <option value="6">Junho</option>
                        <option value="7">Julho</option>
                        <option value="8">Agosto</option>
                        <option value="9">Setembro</option>
                        <option value="10">Outubro</option>
                        <option value="11">Novembro</option>
                        <option value="12">Dezembro</option>
                    </select>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Ano</label>
                    <select name="ano" class="w-full p-4 rounded-2xl border border-slate-200 bg-white text-slate-700 font-medium appearance-none focus:outline-none focus:ring-2 focus:ring-meta-destaque/20 transition-all cursor-pointer">
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026" selected>2026</option>
                    </select>
                </div>

                <div class="flex gap-4 pt-6 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('modalRelatorio')" class="flex-1 py-4 border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all">Cancelar</button>
                    <button type="submit" class="flex-1 py-4 bg-meta-destaque hover:opacity-90 text-white font-bold rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-download"></i> Baixar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.labels_meses = <?= json_encode($labels_meses) ?>;
        window.dados_receitas = <?= json_encode($dados_receitas) ?>;
        window.dados_despesas = <?= json_encode($dados_despesas) ?>;
        window.dados_lucro = <?= json_encode($dados_lucro) ?>;
        window.categorias_labels = <?= json_encode($categorias_labels) ?>;
        window.categorias_valores = <?= json_encode($categorias_valores) ?>;
    </script>

    <script id="chart-data" type="application/json">
        <?= json_encode([
            'labels' => $labels_meses,
            'receitas' => $dados_receitas,
            'despesas' => $dados_despesas,
            'lucro' => $dados_lucro,
            'catLabels' => $categorias_labels,
            'catValores' => $categorias_valores
        ]) ?>
    </script>
    
    <script src="../assets/js/dashboardUsuario.js"></script>

    <script>
        const configSalvaNoBanco = <?= $config_dashboard_db ?>;

        if (configSalvaNoBanco) {
            // Aqui você pega o JSON e aplica nas classes do Tailwind ou estilos da página
            // Ex: escondendo as divs que o gerente marcou como "visivel: false"
            aplicarConfiguracoesNaTela(configSalvaNoBanco);
        }


        document.addEventListener("DOMContentLoaded", () => {
            const configSalva = localStorage.getItem('metaCashDashboardConfig');
            if (configSalva) {
                try {
                    const config = JSON.parse(configSalva);

                    // 1. Aplica a Fonte Dinâmica alterando o valor da variável mapeada no Tailwind
                    if (config.fonte) {
                        document.documentElement.style.setProperty('--meta-font', config.fonte);
                    }

                    // 2. Aplica o Tamanho de Fonte Global
                    if (config.tamanhoFonte) {
                        const mainElement = document.querySelector('main');
                        if (mainElement) {
                            if (config.tamanhoFonte === 'small') mainElement.className = "flex-1 p-8 ml-64 min-h-screen overflow-y-auto box-border text-xs";
                            if (config.tamanhoFonte === 'medium') mainElement.className = "flex-1 p-8 ml-64 min-h-screen overflow-y-auto box-border text-sm";
                            if (config.tamanhoFonte === 'large') mainElement.className = "flex-1 p-8 ml-64 min-h-screen overflow-y-auto box-border text-base";
                            if (config.tamanhoFonte === 'xlarge') mainElement.className = "flex-1 p-8 ml-64 min-h-screen overflow-y-auto box-border text-lg";
                        }
                    }

                    // 3. Aplica os Textos Personalizados
                    if (config.textos) {
                        if (config.textos.titulo_pagina) document.getElementById('txt-titulo_pagina').textContent = config.textos.titulo_pagina;
                        if (config.textos.card_saldo) {
                            document.getElementById('txt-card_saldo').textContent = config.textos.card_saldo;
                            document.getElementById('label-card_saldo').textContent = config.textos.card_saldo;
                        }
                        if (config.textos.card_receitas) document.getElementById('label-card_receitas').textContent = config.textos.card_receitas;
                        if (config.textos.card_despesas) document.getElementById('label-card_despesas').textContent = config.textos.card_despesas;
                        if (config.textos.titulo_grafico_receitas) {
                            const node = document.getElementById('txt-titulo_grafico_receitas');
                            if(node) node.innerHTML = `<i class="fas fa-chart-line text-slate-400"></i> ${config.textos.titulo_grafico_receitas}`;
                        }
                        if (config.textos.titulo_grafico_despesas) {
                            const node = document.getElementById('txt-titulo_grafico_despesas');
                            if(node) node.innerHTML = `<i class="fas fa-chart-pie text-slate-400"></i> ${config.textos.titulo_grafico_despesas}`;
                        }
                    }

                    // 4. Gerencia Ocultação e Tamanhos de Widgets Ativos
                    if (config.widgets) {
                        const widgetSaldo = document.getElementById('widget-saldo_total');
                        if (widgetSaldo && config.widgets.saldo_total) {
                            if (!config.widgets.saldo_total.visivel) {
                                widgetSaldo.classList.add('hidden');
                            } else {
                                const txtSaldo = document.getElementById('txt-saldo_container');
                                if (config.widgets.saldo_total.tamanho === 'P') txtSaldo.className = "text-2xl font-bold mb-4 tracking-tighter text-white";
                                if (config.widgets.saldo_total.tamanho === 'M') txtSaldo.className = "text-4xl font-bold mb-6 tracking-tighter text-white";
                                if (config.widgets.saldo_total.tamanho === 'G') txtSaldo.className = "text-5xl font-bold mb-8 tracking-tighter text-white";
                            }
                        }

                        if (config.widgets.receitas && !config.widgets.receitas.visivel) document.getElementById('widget-receitas').classList.add('hidden');
                        if (config.widgets.despesas && !config.widgets.despesas.visivel) document.getElementById('widget-card_despesas').classList.add('hidden');
                        if (config.widgets.total_transacoes && !config.widgets.total_transacoes.visivel) document.getElementById('widget-total_transacoes').classList.add('hidden');
                        if (config.widgets.grafico_despesas && !config.widgets.grafico_despesas.visivel) document.getElementById('widget-grafico_despesas').classList.add('hidden');
                    }
                } catch (e) {
                    console.error("Erro ao sincronizar modificações do localStorage:", e);
                }
            }
        });
    </script>
>>>>>>> Stashed changes
</body>
</html>
