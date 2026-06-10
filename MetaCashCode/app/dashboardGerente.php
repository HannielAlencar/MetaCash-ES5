<?php
include(__DIR__ . '/config.php');

$cards = isset($cards) ? $cards : [];
$transacoes = isset($transacoes) ? $transacoes : [];

// --- LÓGICA DE CÁLCULO DE SALDO REAL ---
$total_receitas = 0;
$total_despesas = 0;

if (is_array($transacoes)) {
    foreach ($transacoes as $tr) {
        $valor = (float)$tr['valor'];
        if ($tr['tipo'] == 'e') {
            $total_receitas += $valor;
        } elseif ($tr['tipo'] == 's') {
            $total_despesas += $valor;
        }
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
        
        <!-- SIDEBAR FIXA -->
        <aside class="w-64 bg-[#0f172a] text-white p-4 flex flex-col fixed h-screen shrink-0 z-40">
            <div class="flex items-center gap-3 mb-10 px-2 pt-2">
                <!-- LOGO COM PROTEÇÃO CONTRA LOOP DE ERRO -->
                <img src="../assets/img/logo_empresas.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover" onerror="this.onerror=null; this.src='../DashboardGerente/image_75793b.png'; this.onerror=function(){this.src='https://ui-avatars.com/api/?name=MetaCash&background=2dd4bf&color=0f172a';}">
                <div class="flex flex-col">
                    <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
                    <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Gestão Empresarial</span>
                </div>
            </div>

            <!-- Navegação principal com fonte e tamanho sincronizados com o Dashboard de Usuário -->
            <nav class="flex-1 space-y-2">
                <!-- Dashboard -->
                <a href="../app/dashboardGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#2dd4bf] text-white shadow-lg transition">
                    <i class="fas fa-th-large w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <!-- Transações -->
                <a href="../app/TransacoesGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-exchange-alt w-5"></i>
                    <span class="font-medium">Transações</span>
                </a>
                <!-- Equipe -->
                <a href="../app/gerenciaEquipe.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-users w-5"></i>
                    <span class="font-medium">Equipe</span>
                </a>
                <!-- Gerenciar Páginas -->
                <a href="../app/gerenciaPaginas.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="font-medium">Gerenciar Páginas</span>
                </a>
                <!-- Histórico -->
                <a href="../app/historico.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-history w-5"></i>
                    <span class="font-medium">Histórico</span>
                </a>
                <!-- Configurações (Ativo) -->
                <a href="../app/configuracao.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-cog w-5"></i>
                    <span class="font-medium">Configurações</span>
                </a>

                <!-- Botão de Download na Sidebar (Atualizado com mesmo visual, hover e ícone do fluxo usuário) -->
                <button onclick="toggleModal('modalRelatorio')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition border border-transparent hover:border-slate-700 text-left">
                    <i class="fas fa-file-pdf w-5"></i>
                    <span class="font-medium">Baixar Relatório</span>
                </button>
            </nav>

            <!-- Profile Footer -->
            <div class="mt-auto pt-6 border-t border-slate-800 space-y-4 pb-2">
                <a href="../app/PerfilGerente.php" class="bg-[#1e3a5f]/40 p-3 rounded-2xl flex items-center gap-3 border border-slate-700/50 hover:bg-[#1e3a5f]/60 transition block group">
                    <div class="w-10 h-10 bg-[#2dd4bf] rounded-full flex items-center justify-center text-[#0f172a] font-bold text-lg shrink-0 group-hover:scale-105 transition-transform">U</div>
                    <div class="flex flex-col overflow-hidden">
                        <span class="text-sm font-bold truncate">Usuário</span>
                        <span class="text-[10px] text-gray-400 truncate">usuario@exemplo.com</span>
                    </div>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-400 hover:text-white transition group">
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

            <section class="bg-[#0f1c30] rounded-3xl p-8 text-white mb-8 shadow-2xl relative overflow-hidden">
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
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1"><?php echo $titulo; ?></div>
                            <div class="text-2xl font-black text-slate-800">R$ <?php echo $info['valor']; ?></div>
                        </div>
                    </div>
                <?php 
                    $i_card++;
                endforeach; 
                ?>
            </div>

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

            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Transações Recentes</h3>
                    <a href="../TransaçoesGerente.php/index.php" class="text-xs font-bold text-teal-600 hover:underline uppercase">Ver Extrato Completo</a>
                </div>
                <div class="divide-y divide-slate-50 px-6">
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
                            <div class="w-10 h-10 rounded-full flex items-center justify-center <?php echo $tr['tipo'] == 'e' ? 'bg-teal-50' : 'bg-rose-50'; ?>">
                                <i class="fas <?php echo $tr['tipo'] == 'e' ? 'fa-arrow-up text-teal-500' : 'fa-arrow-down text-rose-500'; ?> text-xs"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-bold text-[#1e293b] leading-tight"><?php echo $tr['descricao'] ?? ($tr['titulo'] ?? 'Sem descrição'); ?></p>
                                <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">
                                    <?php echo $tr['cat']; ?> • <?php echo $data_obj->format('d/m/Y'); ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="font-bold text-sm <?php echo $tr['tipo'] == 'e' ? 'text-teal-500' : 'text-rose-500'; ?>">
                                <?php echo ($tr['tipo'] == 'e' ? '+ ' : '- ') . 'R$ ' . number_format($tr['valor'], 2, ',', '.'); ?>
                            </span>
                        </div>
                    </div>
                  <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

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

    <script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

    // Fecha modais ao clicar fora
    window.onclick = function(event) {
        const mRel = document.getElementById('modalRelatorio');
        if (event.target == mRel) toggleModal('modalRelatorio');
    }
    </script>

    <script>
    const currencyFormatter = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });

    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

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
        // Puxando os dados reais vindos do seu PHP
        let labelsPuros = <?php echo json_encode($categorias_labels ?? []); ?>;
        let dadosPuros = <?php echo json_encode($categorias_valores ?? []); ?>;

        // Fallback: Se vieram vazios, tenta buscar da tag script #financeData 
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

        // Print técnico para você acompanhar no F12 do navegador
        console.log("Labels para a Pizza:", labelsPuros);
        console.log("Valores para a Pizza:", dadosPuros);

        // Se mesmo com o fallback não houver nada, evita que o Chart.js quebre
        if (!labelsPuros || labelsPuros.length === 0) {
            labelsPuros = ["Sem dados cadastrados"];
            dadosPuros = [0];
        }

       new Chart(ctxPizza, {
            type: 'doughnut',
            data: {
                labels: labelsPuros,
                datasets: [{
                    // Mantém o cálculo das fatias normal
                    data: dadosPuros.map(v => Math.abs(Number(v || 0))),
                    
                    // CORREÇÃO: Voltamos com a paleta de cores variadas para cada fatia ficar distinta!
                    backgroundColor: ['#3b82f6', '#2dd4bf', '#8b5cf6', '#f43f5e', '#58cd91', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff' // Uma linha fina branca separando as fatias fica excelente
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
                                // O valor real mantém o sinal de - (menos) se for despesa
                                const valorReal = Number(dadosPuros[index] || 0);
                                const label = context.label || '';
                                
                                // Mostra o valor real com o sinal de positivo/negativo correto no hover
                                return `${label}: ${currencyFormatter.format(valorReal)}`;
                            }
                        }
                    }
                }
            }
        });
    }
};

function toggleRelatorioModal() {
            const modal = document.getElementById('modalRelatorio');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
}
    
    </script>
</body>
</html>