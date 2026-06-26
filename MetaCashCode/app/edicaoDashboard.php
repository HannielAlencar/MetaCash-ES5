<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Editor: Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // SCRIPT PARA CARREGAR AS CORES DINÂMICAS DO LOCALSTORAGE
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

        // CONFIGURAÇÃO DOS APELIDOS DE COR NO TAILWIND
        tailwind.config = { theme: { extend: { colors: { 
            meta: { 
                menu: 'var(--meta-menu)', 
                btn1: 'var(--meta-btn1)', 
                destaque: 'var(--meta-destaque)', 
                btn2: 'var(--meta-btn2)', 
                clara: 'var(--meta-clara)', 
                fundo: 'var(--meta-fundo)' 
            }
        }}}};
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght=300;400;500;600;700;800&display=swap');
        :root {
            /* Cores de backup caso o localStorage esteja limpo */
            --meta-menu: #0F172A;
            --meta-btn1: #1E293B;
            --meta-destaque: #2D4BF0;
            --meta-btn2: #2DD4BF;
            --meta-clara: #38BDF8;
            --meta-fundo: #F8FAFC;
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex min-h-screen bg-meta-fundo text-slate-800">

    <aside class="w-64 bg-meta-menu text-white p-4 flex flex-col fixed h-screen shrink-0 z-40 shadow-xl">
        <div class="flex items-center gap-3 mb-10 px-2 pt-2">
            <img src="../assets/img/logo_empresas.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover" onerror="this.onerror=null; this.src='../DashboardGerente/image_75793b.png'; this.onerror=function(){this.src='https://ui-avatars.com/api/?name=MetaCash&background=2dd4bf&color=0f172a';}">
            <div class="flex flex-col">
                <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
                <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Gestão Empresarial</span>
            </div>
        </div>

        <nav class="flex-1 space-y-2">
            <a href="../app/DashboardGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:bg-meta-btn1 hover:text-white transition">
                <i class="fas fa-th-large w-5 text-gray-400"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="../app/TransacoesGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:bg-meta-btn1 hover:text-white transition">
                <i class="fas fa-exchange-alt w-5 text-gray-400"></i>
                <span class="font-medium">Transações</span>
            </a>
            <a href="../app/gerenciaEquipe.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:bg-meta-btn1 hover:text-white transition">
                <i class="fas fa-users w-5 text-gray-400"></i>
                <span class="font-medium">Equipe</span>
            </a>
            <a href="../app/gerenciaPaginas.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-meta-destaque text-white shadow-lg transition font-semibold">
                <i class="fas fa-file-alt w-5"></i>
                <span class="font-medium">Gerenciar Páginas</span>
            </a>
            <a href="../app/historico.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:bg-meta-btn1 hover:text-white transition">
                <i class="fas fa-history w-5 text-gray-400"></i>
                <span class="font-medium">Histórico</span>
            </a>
            <a href="../app/configuracao.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:bg-meta-btn1 hover:text-white transition">
                <i class="fas fa-cog w-5 text-gray-400"></i>
                <span class="font-medium">Configurações</span>
            </a>

            <button onclick="toggleRelatorioModal()" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:bg-meta-btn1 hover:text-white transition border border-transparent hover:border-slate-700/50 text-left">
                <i class="fas fa-file-pdf w-5 text-gray-400"></i>
                <span class="font-medium">Baixar Relatório</span>
            </button>
        </nav>

        <div class="mt-auto pt-6 border-t border-slate-700/50 space-y-4 pb-2">
            <a href="../PerfilGerente.php/index.php" class="bg-meta-btn1/50 p-3 rounded-2xl flex items-center gap-3 border border-slate-700/30 hover:bg-meta-btn1/80 transition block group">
                <div class="w-10 h-10 bg-meta-destaque rounded-full flex items-center justify-center text-white font-bold text-lg shrink-0 group-hover:scale-105 transition-transform">U</div>
                <div class="flex flex-col overflow-hidden">
                    <span class="text-sm font-bold truncate text-white">Usuário</span>
                    <span class="text-[10px] text-gray-300 truncate">usuario@exemplo.com</span>
                </div>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-400 hover:text-white transition group">
                <i class="fas fa-sign-out-alt rotate-180 group-hover:text-red-400 transition-colors"></i>
                <span class="font-medium">Sair</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-8">
        <header class="mb-6">
            <a href="../app/gerenciaPaginas.php" class="text-sm text-meta-destaque font-semibold mb-2 block hover:underline"><i class="fas fa-arrow-left mr-2"></i> Voltar ao Dashboard</a>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-meta-destaque flex items-center justify-center rounded-lg text-white"><i class="fas fa-th-large"></i></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Editor: Dashboard</h2>
                    <p class="text-slate-500">Personalize a aparência e widgets do seu dashboard</p>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-2 space-y-6">
                <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-800 mb-4"><i class="fas fa-cog mr-2 text-slate-400"></i> Configurações Gerais</h4>
                    <label class="text-xs font-bold text-slate-500 uppercase">Fonte</label>
                    <select id="select-fonte" class="w-full border rounded-lg p-3 mt-1 mb-4 bg-white">
                        <option value="Inter">Inter</option>
                        <option value="Roboto">Roboto</option>
                    </select>
                    <label class="text-xs font-bold text-slate-500 uppercase">Tamanho da Fonte</label>
                    <div id="btn-group-fonte" class="grid grid-cols-2 gap-4 mt-1 mb-2">
                        <button data-size="small" class="border py-2 rounded-lg text-sm text-slate-600 transition">Pequeno</button>
                        <button data-size="medium" class="bg-meta-destaque text-white py-2 rounded-lg text-sm font-semibold transition">Médio</button>
                        <button data-size="large" class="border py-2 rounded-lg text-sm text-slate-600 transition">Grande</button>
                        <button data-size="xlarge" class="border py-2 rounded-lg text-sm text-slate-600 transition">Extra Grande</button>
                    </div>
                </section>


                <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-800 mb-6 flex items-center"><i class="fas fa-pen mr-2 text-slate-400"></i> Textos Personalizados</h4>
                    <div class="space-y-4" id="container-textos">
                        <div>
                            <label class="text-[10px] uppercase font-bold text-slate-500">Título da Página</label>
                            <input data-id="titulo_pagina" maxlength="100" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-meta-destaque outline-none" value="Visão Geral Financeira">
                            <div class="text-right text-[11px] text-slate-400 mt-0.5"><span id="char-titulo_pagina">0</span> / 100</div>
                        </div>
                        <div>
                            <label class="text-[10px] uppercase font-bold text-slate-500">Card de Saldo</label>
                            <input data-id="card_saldo" maxlength="100" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-meta-destaque outline-none" value="Saldo Total">
                            <div class="text-right text-[11px] text-slate-400 mt-0.5"><span id="char-card_saldo">0</span> / 100</div>
                        </div>
                        <div>
                            <label class="text-[10px] uppercase font-bold text-slate-500">Card de Receitas</label>
                            <input data-id="card_receitas" maxlength="100" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-meta-destaque outline-none" value="Receitas">
                            <div class="text-right text-[11px] text-slate-400 mt-0.5"><span id="char-card_receitas">0</span> / 100</div>
                        </div>
                        <div>
                            <label class="text-[10px] uppercase font-bold text-slate-500">Card de Despesas</label>
                            <input data-id="card_despesas" maxlength="100" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-meta-destaque outline-none" value="Despesas">
                            <div class="text-right text-[11px] text-slate-400 mt-0.5"><span id="char-card_despesas">0</span> / 100</div>
                        </div>
                        <div>
                            <label class="text-[10px] uppercase font-bold text-slate-500">Título do Gráfico de Receitas</label>
                            <input data-id="titulo_grafico_receitas" maxlength="100" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-meta-destaque outline-none" value="Receitas vs Despesas">
                            <div class="text-right text-[11px] text-slate-400 mt-0.5"><span id="char-titulo_grafico_receitas">0</span> / 100</div>
                        </div>
                        <div>
                            <label class="text-[10px] uppercase font-bold text-slate-500">Título do Gráfico de Despesas</label>
                            <input data-id="titulo_grafico_despesas" maxlength="100" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-meta-destaque outline-none" value="Despesas por Categoria">
                            <div class="text-right text-[11px] text-slate-400 mt-0.5"><span id="char-titulo_grafico_despesas">0</span> / 100</div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-span-1">
                <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm sticky top-6">
                    <h4 class="font-bold text-slate-800 mb-4" id="contador-widgets">Widgets Ativos (5/5)</h4>
                    <div class="space-y-3" id="container-widgets">
                        <div class="border rounded-lg p-3 text-xs widget-item" data-widget="saldo_total">
                            <div class="flex justify-between items-center font-bold mb-2">
                                <span>1 <i class="fas fa-dollar-sign ml-1"></i> Saldo Total</span>
                                <div class="flex gap-2 text-slate-400">
                                    <i class="fas fa-eye cursor-pointer btn-visibilidade text-slate-800"></i>
                                </div>
                            </div>
                        </div>
                        <div class="border rounded-lg p-3 flex justify-between items-center text-xs widget-item" data-widget="receitas">
                            <span>2 Receitas</span>
                            <div class="text-slate-800 flex gap-2">
                                <i class="fas fa-eye cursor-pointer btn-visibilidade"></i>
                            </div>
                        </div>
                        <div class="border rounded-lg p-3 flex justify-between items-center text-xs widget-item" data-widget="despesas">
                            <span>3 Despesas</span>
                            <div class="text-slate-800 flex gap-2">
                                <i class="fas fa-eye cursor-pointer btn-visibilidade"></i>
                            </div>
                        </div>
                        <div class="border rounded-lg p-3 flex justify-between items-center text-xs widget-item" data-widget="total_transacoes">
                            <span>4 Total de Transações</span>
                            <div class="text-slate-800 flex gap-2">
                                <i class="fas fa-eye cursor-pointer btn-visibilidade"></i>
                            </div>
                        </div>
                        <div class="border rounded-lg p-3 flex justify-between items-center text-xs widget-item" data-widget="grafico_despesas">
                            <span>5 Gráfico de Despesas</span>
                            <div class="text-slate-800 flex gap-2">
                                <i class="fas fa-eye cursor-pointer btn-visibilidade"></i>
                            </div>
                        </div>
                    </div>
                    <button onclick="salvarConfiguracoes()" class="w-full mt-6 bg-meta-menu text-white py-3 rounded-xl font-bold shadow-md hover:opacity-95 transition flex items-center justify-center gap-2"><i class="fas fa-save"></i> Salvar Alterações</button>
                    <button onclick="restaurarPadrao()" class="w-full mt-3 border border-red-200 text-red-500 py-3 rounded-xl font-bold hover:bg-red-50 transition flex items-center justify-center gap-2"><i class="fas fa-undo"></i> Restaurar Padrão</button>
                </section>
            </div>
            
        </div>
    </main>

    <div id="modalRelatorio" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-900">Baixar Relatório</h3>
                <button onclick="toggleRelatorioModal()" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <p class="text-sm text-slate-500 mb-6">Selecione o formato e o período desejado para exportação dos dados atuais.</p>
            <div class="space-y-4">
                <button onclick="toggleRelatorioModal(); alert('PDF Gerado com sucesso!');" class="w-full bg-meta-destaque text-white py-3 rounded-xl font-bold hover:opacity-95 transition flex items-center justify-center gap-2"><i class="fas fa-file-pdf"></i> Exportar como PDF</button>
            </div>
        </div>
    </div>

    <script>
    // ESTADO GLOBAL DA PÁGINA (Valores padrão)
    const defaults = {
        fonte: 'Inter',
        tamanhoFonte: 'medium',
        categorias: {
            venda_produtos: true, prestacao_servicos: true, rendimentos: true, outras_receitas: true,
            folha_pagamento: true, despesas_operacionais: true, fornecedores: true, marketing_vendas: true,
            impostos_taxas: true, ti_equipamentos: true, outras_despesas: true
        },
        textos: {
            titulo_pagina: "Visão Geral Financeira",
            card_saldo: "Saldo Total",
            card_receitas: "Receitas",
            card_despesas: "Despesas",
            titulo_grafico_receitas: "Receitas vs Despesas",
            titulo_grafico_despesas: "Despesas por Categoria"
        },
        widgets: {
            saldo_total: { visivel: true, tamanho: 'G' },
            receitas: { visivel: true },
            despesas: { visivel: true },
            total_transacoes: { visivel: true },
            grafico_despesas: { visivel: true }
        }
    };

    let estado = JSON.parse(JSON.stringify(defaults));

    // INICIALIZAÇÃO
    document.addEventListener("DOMContentLoaded", () => {
        carregarEstado();
        renderizarInterface();
        registrarEventos();
    });

    function carregarEstado() {
        const salvo = localStorage.getItem('metaCashDashboardConfig');
        if (salvo) {
            try { estado = JSON.parse(salvo); } catch(e) { console.error(e); }
        }
    }

    function renderizarInterface() {
        // 1. Fonte
        document.getElementById('select-fonte').value = estado.fonte;
        atualizarBotoesTamanhoFonte();

        // 2. Checkboxes de Categorias
        Object.keys(estado.categorias).forEach(cat => {
            const cb = document.querySelector(`input[value="${cat}"]`);
            if(cb) cb.checked = estado.categorias[cat];
        });
        atualizarContadoresCategorias();

        // 3. Textos e atualizadores de contadores de caracteres
        Object.keys(estado.textos).forEach(id => {
            const input = document.querySelector(`input[data-id="${id}"]`);
            if(input) {
                input.value = estado.textos[id];
                atualizarContadorCaracteresIndiv(id, input.value.length);
            }
        });

        // 4. Widgets
        Object.keys(estado.widgets).forEach(wId => {
            const widgetEl = document.querySelector(`.widget-item[data-widget="${wId}"]`);
            if(widgetEl) {
                const olho = widgetEl.querySelector('.btn-visibilidade');
                if(estado.widgets[wId].visivel) {
                    olho.className = "fas fa-eye cursor-pointer btn-visibilidade text-slate-800";
                    widgetEl.classList.remove('opacity-50');
                } else {
                    olho.className = "fas fa-eye-slash cursor-pointer btn-visibilidade text-slate-400";
                    widgetEl.classList.add('opacity-50');
                }

                // Se tiver controle de tamanho interno
                if(estado.widgets[wId].tamanho) {
                    const btnTamanho = widgetEl.querySelector(`.btn-sizes button[data-size="${estado.widgets[wId].tamanho}"]`);
                    if(btnTamanho) alternarEstiloBotaoTamanhoWidget(widgetEl, btnTamanho);
                }
            }
        });
        atualizarContadorWidgets();
    }

    function registrarEventos() {
        // Evento select fonte
        document.getElementById('select-fonte').addEventListener('change', (e) => {
            estado.fonte = e.target.value;
        });

        // Eventos botões tamanho da fonte geral
        document.getElementById('btn-group-fonte').addEventListener('click', (e) => {
            const btn = e.target.closest('button');
            if(!btn) return;
            estado.tamanhoFonte = btn.dataset.size;
            atualizarBotoesTamanhoFonte();
        });

        // Eventos de checkboxes mutáveis
        document.querySelectorAll('.checkboxes-container input').forEach(cb => {
            cb.addEventListener('change', (e) => {
                estado.categorias[e.target.value] = e.target.checked;
                atualizarContadoresCategorias();
            });
        });

        // Evento Marcar/Desmarcar todas as categorias
        document.querySelectorAll('.btn-toggle-todas').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const container = e.target.closest('div').parentNode;
                const checkboxes = container.querySelectorAll('input[type="checkbox"]');
                const algumaMarcada = Array.from(checkboxes).some(cb => cb.checked);
                
                checkboxes.forEach(cb => {
                    cb.checked = !algumaMarcada;
                    estado.categorias[cb.value] = !algumaMarcada;
                });
                atualizarContadoresCategorias();
            });
        });

        // Eventos inputs de texto com contador de caracteres
        document.querySelectorAll('#container-textos input').forEach(input => {
            input.addEventListener('input', (e) => {
                const id = e.target.dataset.id;
                estado.textos[id] = e.target.value;
                atualizarContadorCaracteresIndiv(id, e.target.value.length);
            });
        });

        // Eventos de Visibilidade dos Widgets
        document.querySelectorAll('.btn-visibilidade').forEach(olho => {
            olho.addEventListener('click', (e) => {
                const item = e.target.closest('.widget-item');
                const wId = item.dataset.widget;
                estado.widgets[wId].visivel = !estado.widgets[wId].visivel;
                renderizarInterface();
            });
        });

        // Eventos de tamanho internos do Widget Saldo Total
        document.querySelectorAll('.btn-sizes button').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const item = e.target.closest('.widget-item');
                const wId = item.dataset.widget;
                estado.widgets[wId].tamanho = e.target.dataset.size;
                alternarEstiloBotaoTaches(item, e.target);
            });
        });
    }

    // Auxiliares de estilização e contagem
    function atualizarBotoesTamanhoFonte() {
        document.querySelectorAll('#btn-group-fonte button').forEach(b => {
            if(b.dataset.size === estado.tamanhoFonte) {
                b.className = "bg-meta-destaque text-white py-2 rounded-lg text-sm font-semibold transition";
            } else {
                b.className = "border py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-50 transition";
            }
        });
    }

    function alternarEstiloBotaoTamanhoWidget(container, btnAtivo) {
        container.querySelectorAll('.btn-sizes button').forEach(b => {
            b.className = "border px-3 py-1 rounded hover:bg-slate-50 transition text-slate-600";
        });
        btnAtivo.className = "bg-meta-destaque text-white px-3 py-1 rounded font-semibold transition";
    }

    function atualizarContadoresCategorias() {
        ['receitas', 'despesas'].forEach(tipo => {
            const total = document.querySelectorAll(`input[name="${tipo}"]`).length;
            const ativos = document.querySelectorAll(`input[name="${tipo}"]:checked`).length;
            const label = tipo === 'receitas' ? 'Receitas' : 'Despesas';
            document.querySelector(`#grupo-${tipo} .contador`).innerText = `${label} (${ativos}/${total})`;
            document.querySelector(`#grupo-${tipo} .btn-toggle-todas`).innerText = ativos > 0 ? 'Desmarcar Todas' : 'Marcar Todas';
        });
    }

    function atualizarContadorWidgets() {
        const total = Object.keys(estado.widgets).length;
        const ativos = Object.values(estado.widgets).filter(w => w.visivel).length;
        document.getElementById('contador-widgets').innerText = `Widgets Ativos (${ativos}/${total})`;
    }

    function atualizarContadorCaracteresIndiv(id, totalCaracteres) {
        const span = document.getElementById(`char-${id}`);
        if(span) {
            span.innerText = totalCaracteres;
            // Se chegar no limite, muda a cor do texto para vermelho indicando aviso
            if (totalCaracteres >= 100) {
                span.parentNode.className = "text-right text-[11px] text-red-500 font-medium mt-0.5";
            } else {
                span.parentNode.className = "text-right text-[11px] text-slate-400 mt-0.5";
            }
        }
    }

    // Métodos de persistência principal
    function salvarConfiguracoes() {
        localStorage.setItem('metaCashDashboardConfig', JSON.stringify(estado));
        alert('Configurações salvas com sucesso no sistema!');
    }

    function restaurarPadrao() {
        if(confirm('Tem certeza que deseja restaurar as configurações originais do dashboard?')) {
            localStorage.removeItem('metaCashDashboardConfig');
            estado = JSON.parse(JSON.stringify(defaults));
            renderizarInterface();
        }
    }

    // Modal
    function toggleRelatorioModal() {
        const modal = document.getElementById('modalRelatorio');
        if(modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }
    </script>
</body>
</html>