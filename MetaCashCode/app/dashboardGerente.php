<?php
// Define variáveis padrão vazias para evitar erros caso a inclusão falhe
$cards = [];
$transacoes = [];

// Tentativa inteligente de encontrar o arquivo de configuração e dados
$config_path = __DIR__ . '/config.php';
$config_parent_path = dirname(__DIR__) . '/config.php';

if (file_exists($config_path)) {
    include($config_path);
} elseif (file_exists($config_parent_path)) {
    include($config_parent_path);
} else {
    // Caso o config.php não exista em nenhum lugar, tenta carregar dados.php ou api.php como fallback
    $fallback_data = __DIR__ . '/data.php';
    if (file_exists($fallback_data)) {
        include($fallback_data);
    }
}

// Garante que as variáveis sejam arrays para não quebrar os loops foreach abaixo
$cards = isset($cards) && is_array($cards) ? $cards : [];
$transacoes = isset($transacoes) && is_array($transacoes) ? $transacoes : [];

// --- LÓGICA DE CÁLCULO DE SALDO REAL ---
$total_receitas = 0;
$total_despesas = 0;

foreach ($transacoes as $tr) {
    $valor = (float)($tr['valor'] ?? 0);
    $tipo = $tr['tipo'] ?? '';
    if ($tipo == 'e' || $tipo == 'entrada') {
        $total_receitas += $valor;
    } elseif ($tipo == 's' || $tipo == 'saida' || $tipo == 'despesa') {
        $total_despesas += $valor;
    }
}

$saldo_real_lucro = $total_receitas - $total_despesas;

$labels_meses = isset($labels_meses) ? $labels_meses : ['Set', 'Out', 'Nov', 'Dez', 'Jan', 'Fev', 'Mar'];
$dados_receitas = isset($dados_receitas) ? $dados_receitas : [0,0,0,0,0,0,0];
$dados_despesas = isset($dados_despesas) ? $dados_despesas : [0,0,0,0,0,0,0];

$dados_lucro = [];
foreach ($dados_receitas as $index => $receita) {
    $despesa = isset($dados_despesas[$index]) ? $dados_despesas[$index] : 0;
    $dados_lucro[] = $receita - $despesa;
}

$categorias_labels = isset($categorias_labels) ? $categorias_labels : [];
$categorias_valores = isset($categorias_valores) ? $categorias_valores : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MetaCash - Gestão</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .peer-checked\:bg-custom-dark:checked + div {
            background-color: #1e293b !important;
            color: white !important;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex min-h-screen">
        
        <!-- SIDEBAR IMPORTADA PELO PHP -->
        <?php require_once '../includes/sidebarGerente.php'; ?>

        <!-- CONTEÚDO PRINCIPAL (Tag restabelecida com a margem esquerda e responsividade adequadas) -->
        <main class="flex-1 p-8 ml-64 w-full">

            <!-- Grid de Cards de Resumo -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <?php 
                $icones_cards = [
                    '/MetaCashCode/Usuario/Dashboard/img/sifrao icone.png',
                    '/MetaCashCode/Usuario/Dashboard/img/seta icone.png',
                    '/MetaCashCode/Usuario/Dashboard/img/cartao icone.png',
                    '/MetaCashCode/Usuario/Dashboard/img/bonecos icone.png'
                ];
                $i_card = 0;
                foreach($cards as $titulo => $info): 
                    $icone_atual = isset($icones_cards[$i_card]) ? $icones_cards[$i_card] : '/MetaCashCode/Usuario/Dashboard/img/sifrao icone.png';
                ?>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-[#2dd4bf] transition group">
                        <div class="flex justify-between items-start mb-4">
                            <img src="<?php echo $icone_atual; ?>" alt="Ícone <?php echo htmlspecialchars($titulo, ENT_QUOTES); ?>" class="w-10 h-10 object-contain" />
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1"><?php echo htmlspecialchars($titulo); ?></div>
                            <div class="text-2xl font-black text-slate-800">R$ <?php echo htmlspecialchars($info['valor'] ?? '0,00'); ?></div>
                        </div>
                    </div>
                <?php 
                    $i_card++;
                endforeach; 
                ?>
            </div>

            <!-- Gráficos de Desempenho -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="fas fa-chart-line text-slate-400"></i> Desempenho Mensal (Saldo)</h3>
                    <div class="relative h-[300px] w-full"><canvas id="graficoLinha"></canvas></div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="fas fa-chart-pie text-slate-400"></i> Distribuição por Categorias</h3>
                    <div class="relative h-[300px] w-full"><canvas id="graficoPizza"></canvas></div>
                </div>
            </div>

            <!-- Tabela Transações Recentes -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Transações Recentes</h3>
                    <a href="../TransaçoesGerente.php/index.php" class="text-xs font-bold text-teal-600 hover:underline uppercase">Ver Extrato Completo</a>
                </div>
                <div class="divide-y divide-slate-50 px-6">
                    <?php if (empty($transacoes)): ?>
                        <div class="p-8 text-center text-slate-400 italic">
                            Nenhuma transação registrada até o momento.
                        </div>
                    <?php endif; ?>
                    <?php foreach($transacoes as $id => $tr): 
                        $data_exibicao = trim($tr['data'] ?? '');
                        if ($data_exibicao !== '') {
                            $data_obj = DateTime::createFromFormat('d/m/Y', $data_exibicao);
                            if (!$data_obj) {
                                $data_obj = DateTime::createFromFormat('Y-m-d', $data_exibicao);
                            }
                            if (!$data_obj) {
                                $data_obj = date_create($data_exibicao);
                            }
                            if (!$data_obj) {
                                $data_obj = new DateTime();
                            }
                        } else {
                            $data_obj = new DateTime();
                        }
                    ?>
                        <div class="flex items-center justify-between p-4 border-b last:border-0 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center <?php echo ($tr['tipo'] ?? '') == 'e' ? 'bg-teal-50' : 'bg-rose-50'; ?>">
                                    <i class="fas <?php echo ($tr['tipo'] ?? '') == 'e' ? 'fa-arrow-up text-teal-500' : 'fa-arrow-down text-rose-500'; ?> text-xs"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="font-bold text-[#1e293b] leading-tight"><?php echo htmlspecialchars($tr['descricao'] ?? ($tr['titulo'] ?? ($tr['nome'] ?? 'Sem descrição'))); ?></p>
                                    <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">
                                        <?php echo htmlspecialchars($tr['cat'] ?? 'Geral'); ?> • <?php echo $data_obj->format('d/m/Y'); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="font-bold text-sm <?php echo ($tr['tipo'] ?? '') == 'e' ? 'text-teal-500' : 'text-rose-500'; ?>">
                                    <?php echo (($tr['tipo'] ?? '') == 'e' ? '+ ' : '- ') . 'R$ ' . number_format($tr['valor'] ?? 0, 2, ',', '.'); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- JSON DATA PARA GRÁFICOS -->
    <script id="financeData" type="application/json">
    {
        "labels": <?php echo json_encode($labels_meses ?? []); ?>,
        "receitas": <?php echo json_encode($dados_receitas ?? []); ?>,
        "despesas": <?php echo json_encode($dados_despesas ?? []); ?>,
        "categorias": <?php echo json_encode($categorias ?? []); ?>
    }
    </script>

    <!-- MODAL BAIXAR RELATÓRIO -->
    <div id="modalRelatorio" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-[60] p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h3 class="text-xl font-bold text-slate-800">Baixar Relatório</h3>
                <button onclick="toggleRelatorioModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="gerar_pdf.php" method="GET" target="_blank" class="space-y-6">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-3">Tipo de Transação</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="e" class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Receita</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="s" class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Despesa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="todos" checked class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Ambos</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-3">Período</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="mensal" checked class="hidden peer" onclick="document.getElementById('campoMesRelatorio').style.display='block'">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Mensal</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="anual" class="hidden peer" onclick="document.getElementById('campoMesRelatorio').style.display='none'">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Anual</div>
                        </label>
                    </div>
                </div>

                <div id="campoMesRelatorio">
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Mês</label>
                    <select name="mes" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-teal-500 transition">
                        <option value="01">Janeiro</option><option value="02">Fevereiro</option>
                        <option value="03">Março</option><option value="04">Abril</option>
                        <option value="05" selected>Maio</option><option value="06">Junho</option>
                        <option value="07">Julho</option><option value="08">Agosto</option>
                        <option value="09">Setembro</option><option value="10">Outubro</option>
                        <option value="11">Novembro</option><option value="12">Dezembro</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Ano</label>
                    <select name="ano" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-teal-500 transition">
                        <option value="2026" selected>2026</option>
                        <option value="2025">2025</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-4 border-t">
                    <button type="button" onclick="toggleRelatorioModal()" class="flex-1 py-3 border border-slate-300 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-slate-800 to-teal-600 text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition">
                        <i class="fas fa-download mr-2"></i> Baixar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPTS INTERATIVOS -->
    <script>
    function toggleRelatorioModal() {
        const modal = document.getElementById('modalRelatorio');
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

    // Fecha modais ao clicar fora
    window.onclick = function(event) {
        const mRel = document.getElementById('modalRelatorio');
        if (event.target == mRel) {
            toggleRelatorioModal();
        }
    }
    </script>

    <script>
    const currencyFormatter = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });

    window.onload = function() {
        const ctxLinha = document.getElementById('graficoLinha')?.getContext('2d');
        if(ctxLinha) {
            new Chart(ctxLinha, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($labels_meses ?? []); ?>,
                    datasets: [{
                        label: 'Receitas',
                        data: <?php echo json_encode($dados_receitas ?? []); ?>,
                        borderColor: '#2dd4bf',
                        backgroundColor: 'rgba(45, 212, 191, 0.1)',
                        fill: true, tension: 0.4
                    }, {
                        label: 'Despesas',
                        data: <?php echo json_encode($dados_despesas ?? []); ?>,
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244, 63, 94, 0.05)',
                        fill: true, tension: 0.4
                    }, {
                        label: 'Resultado (R - D)',
                        data: <?php echo json_encode($dados_lucro ?? []); ?>,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: false, borderDash: [5, 5], tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) label += currencyFormatter.format(context.parsed.y);
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        const ctxPizza = document.getElementById('graficoPizza')?.getContext('2d');
        if(ctxPizza) {
            let labelsPuros = <?php echo json_encode($categorias_labels ?? []); ?>;
            let dadosPuros = <?php echo json_encode($categorias_valores ?? []); ?>;

            if ((!labelsPuros || labelsPuros.length === 0) || (!dadosPuros || dadosPuros.length === 0)) {
                const jsonDataEl = document.getElementById('financeData');
                if (jsonDataEl) {
                    try {
                        const dataExtraida = JSON.parse(jsonDataEl.textContent);
                        if (dataExtraida.categorias) {
                            labelsPuros = Object.keys(dataExtraida.categorias);
                            dadosPuros = Object.values(dataExtraida.categorias);
                        }
                    } catch (e) {
                        console.error("Erro ao ler dados do JSON alternativo:", e);
                    }
                }
            }

            if (!labelsPuros || labelsPuros.length === 0) {
                labelsPuros = ["Sem dados cadastrados"];
                dadosPuros = [0];
            }

            new Chart(ctxPizza, {
                type: 'doughnut',
                data: {
                    labels: labelsPuros,
                    datasets: [{
                        data: dadosPuros.map(v => Math.abs(Number(v || 0))),
                        backgroundColor: ['#3b82f6', '#2dd4bf', '#8b5cf6', '#f43f5e', '#58cd91', '#f59e0b'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const valorReal = Number(dadosPuros[index] || 0);
                                    const label = context.label || '';
                                    return `${label}: ${currencyFormatter.format(valorReal)}`;
                                }
                            }
                        }
                    }
                }
            });
        }
    };
    </script>
</body>
</html>