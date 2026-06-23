<?php
// 1. Segurança e Sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php'; 

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id_empresa = $_SESSION['id_empresa'];

$total_receitas = 0;
$total_despesas = 0;
$saldo = 0;
$transacoes_recentes = [];

try {
    // 2. Calcula os Totais da Empresa
    $sql_totais = "SELECT 
        SUM(CASE WHEN tipo_transacao = 'Receita' THEN valor_transacao ELSE 0 END) as receitas,
        SUM(CASE WHEN tipo_transacao = 'Despesa' THEN valor_transacao ELSE 0 END) as despesas
        FROM transacoes WHERE id_empresa = :empresa";
        
    $stmt_totais = $pdo->prepare($sql_totais);
    $stmt_totais->execute([':empresa' => $id_empresa]);
    $resultado_totais = $stmt_totais->fetch();

    if ($resultado_totais) {
        $total_receitas = (float)$resultado_totais['receitas'];
        $total_despesas = (float)$resultado_totais['despesas'];
        $saldo = $total_receitas - $total_despesas;
    }

    // 3. Puxa as últimas 5 transações
    $sql_recentes = "SELECT 
                t.descricao_transacao AS titulo, 
                t.valor_transacao AS valor, 
                t.tipo_transacao AS tipo, 
                c.nome_categoria AS cat,
                TO_CHAR(t.data_transacao, 'DD/MM/YYYY') || ' ' || TO_CHAR(t.data_registro, 'HH24:MI') AS data
            FROM transacoes t
            LEFT JOIN categoria c ON t.id_categoria = c.id_categoria
            WHERE t.id_empresa = :empresa
            ORDER BY t.data_registro DESC, t.id_transacao DESC
            LIMIT 5"; 
            
    $stmt_recentes = $pdo->prepare($sql_recentes);
    $stmt_recentes->execute([':empresa' => $id_empresa]);
    $transacoes_recentes = $stmt_recentes->fetchAll();

} catch (PDOException $e) {
    error_log("Erro no Dashboard: " . $e->getMessage());
}

if (!function_exists('formatarMoeda')) {
    function formatarMoeda($valor) {
        return number_format($valor, 2, ',', '.');
    }
}

$saldo_real_lucro = $saldo;

// Reestruturação dos 4 pequenos cards com classes e ícones FontAwesome nativos e consistentes
$cards_dados = [
    [
        'titulo' => 'Saldo Atual',
        'valor' => formatarMoeda($saldo),
        'classe_bg' => 'bg-meta-destaque/10',
        'classe_icon' => 'fa-wallet text-meta-destaque'
    ],
    [
        'titulo' => 'Total de Receitas',
        'valor' => formatarMoeda($total_receitas),
        'classe_bg' => 'bg-teal-500/10',
        'classe_icon' => 'fa-arrow-trend-up text-teal-500'
    ],
    [
        'titulo' => 'Total de Despesas',
        'valor' => formatarMoeda($total_despesas),
        'classe_bg' => 'bg-rose-500/10',
        'classe_icon' => 'fa-arrow-trend-down text-rose-500'
    ],
    [
        'titulo' => 'Lucro do Período',
        'valor' => formatarMoeda($saldo),
        'classe_bg' => 'bg-meta-btn1/20',
        'classe_icon' => 'fa-chart-line text-meta-btn1'
    ]
];

$meses_nomes = ['', 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
$historico = [];
$labels_meses = [];
$dados_receitas = [];
$dados_despesas = [];
$dados_lucro = [];

for ($i = 5; $i >= 0; $i--) {
    $mes_num = (int)date('n', strtotime("-$i months"));
    $ano_num = date('Y', strtotime("-$i months"));
    $chave = "$ano_num-" . str_pad($mes_num, 2, '0', STR_PAD_LEFT); 
    
    $historico[$chave] = [
        'label' => $meses_nomes[$mes_num],
        'receitas' => 0,
        'despesas' => 0
    ];
}

try {
    $sql_linha = "SELECT 
        TO_CHAR(data_registro, 'YYYY-MM') as mes_ano,
        SUM(CASE WHEN tipo_transacao = 'Receita' THEN valor_transacao ELSE 0 END) as receitas,
        SUM(CASE WHEN tipo_transacao = 'Despesa' THEN valor_transacao ELSE 0 END) as despesas
        FROM transacoes 
        WHERE id_empresa = :empresa 
        AND data_registro >= CURRENT_DATE - INTERVAL '6 months'
        GROUP BY mes_ano";
        
    $stmt_linha = $pdo->prepare($sql_linha);
    $stmt_linha->execute([':empresa' => $id_empresa]);
    $resultados_linha = $stmt_linha->fetchAll();

    foreach ($resultados_linha as $row) {
        $chave = $row['mes_ano'];
        if (isset($historico[$chave])) {
            $historico[$chave]['receitas'] = (float)$row['receitas'];
            $historico[$chave]['despesas'] = (float)$row['despesas'];
        }
    }
} catch (PDOException $e) {
    error_log("Erro no gráfico de linha: " . $e->getMessage());
}

foreach ($historico as $dados) {
    $labels_meses[] = $dados['label'];
    $dados_receitas[] = $dados['receitas'];
    $dados_despesas[] = $dados['despesas'];
    $dados_lucro[] = $dados['receitas'] - $dados['despesas'];
}

$categorias_agrupadas = [];

try {
    $sql_pizza = "SELECT c.nome_categoria, 
                  SUM(CASE WHEN t.tipo_transacao = 'Receita' THEN t.valor_transacao ELSE -t.valor_transacao END) as total 
                  FROM transacoes t 
                  LEFT JOIN categoria c ON t.id_categoria = c.id_categoria 
                  WHERE t.id_empresa = :empresa 
                  GROUP BY c.nome_categoria";
                  
    $stmt_pizza = $pdo->prepare($sql_pizza);
    $stmt_pizza->execute([':empresa' => $id_empresa]);
    $resultado_pizza = $stmt_pizza->fetchAll();

    foreach ($resultado_pizza as $row) {
        $nome = $row['nome_categoria'] ?? 'Geral';
        $categorias_agrupadas[$nome] = (float)$row['total'];
    }
} catch (PDOException $e) {
    error_log("Erro no gráfico de pizza: " . $e->getMessage());
}

if (empty($categorias_agrupadas)) {
    $categorias_labels = ['Sem movimentação'];
    $categorias_valores = [0];
} else {
    $categorias_labels = array_keys($categorias_agrupadas);
    $categorias_valores = array_values($categorias_agrupadas);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        // SCRIPT CORRIGIDO PARA VARIÁVEIS DO TEMA
        (function() {
            const root = document.documentElement;
            
            // Define o padrão correto como fallback obrigatório
            root.style.setProperty('--meta-menu', '#0F2440');
            root.style.setProperty('--meta-btn1', '#204C73');
            root.style.setProperty('--meta-destaque', '#24A6B6');
            root.style.setProperty('--meta-btn2', '#35C59A');
            root.style.setProperty('--meta-clara', '#5DA4C0');
            root.style.setProperty('--meta-fundo', '#FDFEFB');

            // Se existir tema customizado no localStorage, aplica por cima
            const temaSalvo = localStorage.getItem('metaCashTheme');
            if (temaSalvo) {
                try {
                    const cores = JSON.parse(temaSalvo);
                    for (const [key, value] of Object.entries(cores)) {
                        root.style.setProperty(`--meta-${key}`, value);
                    }
                } catch(e) { console.error("Erro ao aplicar tema", e); }
            }
        })();

        tailwind.config = { 
            theme: { 
                extend: { 
                    colors: { 
                        meta: { 
                            menu: 'var(--meta-menu)', 
                            btn1: 'var(--meta-btn1)', 
                            destaque: 'var(--meta-destaque)', 
                            btn2: 'var(--meta-btn2)', 
                            clara: 'var(--meta-clara)', 
                            fundo: 'var(--meta-fundo)' 
                        }
                    }
                }
            }
        };
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        :root {
            --meta-menu: #0F2440;
            --meta-btn1: #204C73;
            --meta-destaque: #24A6B6;
            --meta-btn2: #35C59A;
            --meta-clara: #5DA4C0;
            --meta-fundo: #FDFEFB;
        }
        body { font-family: 'Inter', sans-serif; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboardUsuario.css">
</head>
<body class="bg-gray-50">

    <div class="flex min-h-screen">
        <?php require_once '../includes/sidebar.php'; ?>

        <main class="flex-1 p-8 ml-64 w-full">
            <div class="mb-8">
                <h1 class="text-4xl font-extrabold text-meta-menu tracking-tight">Visão Geral Financeira</h1>
                <p class="text-lg text-[#334155] mt-2">Acompanhe o desempenho financeiro da sua empresa</p>
            </div>

            <section class="bg-meta-menu rounded-3xl p-8 text-white mb-8 shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-wallet text-slate-400"></i>
                        <span class="text-xs text-slate-400 uppercase tracking-widest font-bold">Saldo total</span>
                    </div>
                    <div class="text-5xl font-bold mb-8 tracking-tighter <?php echo $saldo_real_lucro >= 0 ? 'text-white' : 'text-rose-400'; ?>">
                        R$ <?php echo number_format($saldo_real_lucro, 2, ',', '.'); ?>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white/5 border border-white/10 p-5 rounded-2xl flex items-center gap-4">
                            <div class="w-10 h-10 bg-teal-500/20 text-teal-400 rounded-full flex items-center justify-center"><i class="fas fa-arrow-up text-sm"></i></div>
                            <div>
                                <div class="text-xs text-slate-400 font-bold uppercase">Receitas Totais</div>
                                <div class="text-xl font-bold">R$ <?php echo number_format($total_receitas, 2, ',', '.'); ?></div>
                            </div>
                        </div>
                        <div class="bg-white/5 border border-white/10 p-5 rounded-2xl flex items-center gap-4">
                            <div class="w-10 h-10 bg-rose-500/20 text-rose-400 rounded-full flex items-center justify-center"><i class="fas fa-arrow-down text-sm"></i></div>
                            <div>
                                <div class="text-xs text-slate-400 font-bold uppercase">Despesas Totais</div>
                                <div class="text-xl font-bold">R$ <?php echo number_format($total_despesas, 2, ',', '.'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <?php foreach($cards_dados as $card): ?>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-meta-destaque transition group flex flex-col justify-between">
                        <div class="w-10 h-10 <?= $card['classe_bg'] ?> rounded-xl flex items-center justify-center mb-4 text-lg">
                            <i class="fas <?= $card['classe_icon'] ?>"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1"><?= $card['titulo'] ?></div>
                            <div class="text-2xl font-black text-slate-800">R$ <?= $card['valor'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="fas fa-chart-line text-slate-400"></i> Desempenho Mensal (Saldo)</h3>
                    <div class="relative h-[300px] w-full"><canvas id="chartLinha"></canvas></div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="fas fa-chart-pie text-slate-400"></i> Distribuição por Categorias</h3>
                    <div class="relative h-[300px] w-full"><canvas id="chartPizza"></canvas></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Transações Recentes</h3>
                    <a href="../app/transacoes.php" class="text-xs font-bold text-meta-destaque hover:underline uppercase">Ver todas</a>
                </div>
                <div class="divide-y divide-slate-50 px-6">
                  <?php foreach ($transacoes_recentes as $id => $tr): 
                      $data_exibicao = (isset($tr['data']) && !empty($tr['data'])) ? $tr['data'] : date('d/m/Y H:i');
                  ?>
                    <div class="flex items-center justify-between p-4 border-b last:border-0 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center <?php echo $tr['tipo'] === 'Receita' ? 'bg-teal-50' : 'bg-rose-50'; ?>">
                                <i class="fas <?php echo $tr['tipo'] === 'Receita' ? 'fa-arrow-up text-teal-500' : 'fa-arrow-down text-rose-500'; ?> text-xs"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-bold text-[#1e293b] leading-tight"><?php echo $tr['descricao'] ?? ($tr['titulo'] ?? 'Sem descrição'); ?></p>
                                <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">
                                    <?php echo $tr['cat']; ?> • <?php echo $data_exibicao; ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="font-bold text-sm <?php echo $tr['tipo'] === 'Receita' ? 'text-teal-500' : 'text-rose-500'; ?>">
                                <?php echo ($tr['tipo'] === 'Receita' ? '+ ' : '- ') . 'R$ ' . number_format($tr['valor'], 2, ',', '.'); ?>
                            </span>
                        </div>
                    </div>
                  <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
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
</body>
</html>