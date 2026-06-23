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

// 4. PREPARANDO AS VARIÁVEIS QUE O SEU HTML EXIGE:
$saldo_real_lucro = $saldo;

$cards = [
    'Saldo Atual' => ['id' => 'card_saldo', 'valor' => formatarMoeda($saldo), 'porcentagem' => '', 'cor' => 'text-slate-500'],
    'Total de Receitas' => ['id' => 'card_receitas', 'valor' => formatarMoeda($total_receitas), 'porcentagem' => '+', 'cor' => 'text-meta-btn2'],
    'Total de Despesas' => ['id' => 'card_despesas', 'valor' => formatarMoeda($total_despesas), 'porcentagem' => '-', 'cor' => 'text-rose-500'],
    'Lucro do Período' => ['id' => 'card_lucro', 'valor' => formatarMoeda($saldo), 'porcentagem' => '', 'cor' => 'text-meta-clara']
];

$meses_nomes = ['', 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
$historico = [];
$labels_meses = [];
$dados_receitas = [];
$dados_despesas = [];
$dados_lucro = [];

// Inicializa os últimos 6 meses com zero
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
    // 5.2. Busca no banco os dados agrupados por mês
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
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Dashboard</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        // SCRIPT PARA CARREGAR AS CORES DINÂMICAS DA SIDEBAR E DA PÁGINA
        (function() {
            const temaSalvo = localStorage.getItem('metaCashTheme');
            if (temaSalvo) {
                try {
                    const cores = JSON.parse(temaSalvo);
                    const root = document.documentElement;
                    for (const [key, value] of Object.entries(cores)) {
                        root.style.setProperty(`--meta-${key}`, value);
                    }
                } catch(e) { console.error("Erro ao aplicar tema", e); }
            }
        })();

        // ATUALIZADO: Configuração do Tailwind para usar a variável CSS como a fonte padrão (sans)
        tailwind.config = { 
            theme: { 
                extend: { 
                    fontFamily: {
                        sans: ['var(--meta-font)', 'Inter', 'sans-serif']
                    },
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap');
        :root {
            --meta-menu: #0F2440;
            --meta-btn1: #204C73;
            --meta-destaque: #24A6B6;
            --meta-btn2: #35C59A;
            --meta-clara: #5DA4C0;
            --meta-fundo: #FDFEFB;
            /* Definição inicial da variável da fonte */
            --meta-font: 'Inter';
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboardGerente.css">
</head>
<body class="bg-meta-fundo text-slate-800 font-sans transition-colors duration-200 min-h-screen antialiased flex overflow-x-hidden">

    <?php include_once '../includes/sidebarGerente.php'; ?>

    <main class="flex-1 p-8 ml-64 min-h-screen overflow-y-auto box-border">
        <div class="max-w-full w-full mx-auto">
            <div class="mb-8">
                <h1 id="txt-titulo_pagina" class="text-4xl font-extrabold text-slate-900 tracking-tight">Visão Geral Financeira</h1>
                <p class="text-lg text-slate-600 mt-2">Acompanhe o desempenho financeiro da sua empresa</p>
            </div>

            <section id="widget-saldo_total" class="bg-meta-menu rounded-3xl p-8 text-white mb-8 shadow-2xl relative overflow-hidden w-full transition-all duration-300">
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-wallet text-slate-400"></i>
                        <span id="txt-card_saldo" class="text-xs text-slate-400 uppercase tracking-widest font-bold">Saldo total</span>
                    </div>
                    <div id="txt-saldo_container" class="text-5xl font-bold mb-8 tracking-tighter <?= $saldo_real_lucro >= 0 ? 'text-white' : 'text-rose-400'; ?>">
                        R$ <?= number_format($saldo_real_lucro, 2, ',', '.'); ?>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white/5 border border-white/10 p-5 rounded-2xl flex items-center gap-4">
                            <div class="w-10 h-10 bg-meta-btn2/20 text-meta-btn2 rounded-full flex items-center justify-center"><i class="fas fa-arrow-up text-sm"></i></div>
                            <div>
                                <div class="text-xs text-slate-400 font-bold uppercase">Receitas Totais</div>
                                <div class="text-xl font-bold text-white">R$ <?= number_format($total_receitas, 2, ',', '.'); ?></div>
                            </div>
                        </div>
                        <div class="bg-white/5 border border-white/10 p-5 rounded-2xl flex items-center gap-4">
                            <div class="w-10 h-10 bg-rose-500/20 text-rose-400 rounded-full flex items-center justify-center"><i class="fas fa-arrow-down text-sm"></i></div>
                            <div>
                                <div class="text-xs text-slate-400 font-bold uppercase">Despesas Totais</div>
                                <div class="text-xl font-bold text-white">R$ <?= number_format($total_despesas, 2, ',', '.'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <?php 
                $icones_cards = ['../assets/img/iconeSifrao.png', '../assets/img/iconeSeta.png', '../assets/img/iconeCartao.png', '../assets/img/iconeBonecos.png'];
                $i_card = 0;
                foreach($cards as $titulo => $info): 
                    $icone_atual = isset($icones_cards[$i_card]) ? $icones_cards[$i_card] : '../assets/img/iconeSifrao.png';
                ?>
                    <div id="widget-<?= $info['id']; ?>" class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-meta-destaque transition group shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <img src="<?= $icone_atual; ?>" alt="Ícone Métrica" class="w-10 h-10 object-contain">
                        </div>
                        <div>
                            <div id="label-<?= $info['id']; ?>" class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1"><?= $titulo; ?></div>
                            <div class="text-2xl font-black text-slate-800">R$ <?= $info['valor']; ?></div>
                        </div>
                    </div>
                <?php 
                    $i_card++;
                endforeach; 
                ?>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div id="widget-receitas" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 id="txt-titulo_grafico_receitas" class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="fas fa-chart-line text-slate-400"></i> Desempenho Mensal (Saldo)</h3>
                    <div class="relative h-[300px] w-full"><canvas id="chartLinha"></canvas></div>
                </div>
                <div id="widget-grafico_despesas" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 id="txt-titulo_grafico_despesas" class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="fas fa-chart-pie text-slate-400"></i> Distribuição por Categorias</h3>
                    <div class="relative h-[300px] w-full"><canvas id="chartPizza"></canvas></div>
                </div>
            </div>

            <div id="widget-total_transacoes" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm mb-10">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Transações Recentes</h3>
                    <a href="../app/transacoesGerente.php" class="text-xs font-bold text-meta-destaque hover:underline uppercase">Ver todas</a>
                </div>
                <div class="divide-y divide-slate-100 px-6">
                  <?php foreach ($transacoes_recentes as $tr): 
                      $data_exibicao = (!empty($tr['data'])) ? $tr['data'] : date('d/m/Y H:i');
                  ?>
                    <div class="flex items-center justify-between py-4 hover:bg-slate-50/50 transition-colors px-2 rounded-xl my-1">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center <?= $tr['tipo'] === 'Receita' ? 'bg-meta-btn2/10' : 'bg-rose-50'; ?>">
                                <i class="fas <?= $tr['tipo'] === 'Receita' ? 'fa-arrow-up text-meta-btn2' : 'fa-arrow-down text-rose-500'; ?> text-xs"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-bold text-slate-800 leading-tight"><?= htmlspecialchars($tr['titulo'] ?? 'Sem descrição', ENT_QUOTES, 'UTF-8'); ?></p>
                                <p class="text-[10px] text-slate-400 uppercase font-semibold tracking-wider mt-0.5">
                                    <?= htmlspecialchars($tr['cat'] ?? 'Geral', ENT_QUOTES, 'UTF-8'); ?> • <?= $data_exibicao; ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="font-bold text-sm <?= $tr['tipo'] === 'Receita' ? 'text-meta-btn2' : 'text-rose-500'; ?>">
                                <?= ($tr['tipo'] === 'Receita' ? '+ ' : '- ') . 'R$ ' . number_format($tr['valor'], 2, ',', '.'); ?>
                            </span>
                        </div>
                    </div>
                  <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

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
</body>
</html>