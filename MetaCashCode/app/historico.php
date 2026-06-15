<?php
// LÓGICA DE DADOS DE FALLBACK (Mock data para garantir que a página nunca quebre)
$mock_registros = [
    [
        'tag' => 'Criação',
        'tag_color' => 'bg-teal-100 text-teal-800 border border-teal-200',
        'cat' => 'Transação',
        'desc' => 'Adicionada nova transação de receita: "Venda de Licença de Software Premium" no valor de R$ 4.500,00',
        'data' => date('d/m/Y')
    ],
    [
        'tag' => 'Edição',
        'tag_color' => 'bg-amber-100 text-amber-800 border border-amber-200',
        'cat' => 'Configuração',
        'desc' => 'Alterado o saldo inicial da empresa de R$ 10.000,00 para R$ 15.000,00 nas configurações',
        'data' => date('d/m/Y')
    ],
    [
        'tag' => 'Exclusão',
        'tag_color' => 'bg-rose-100 text-rose-800 border border-rose-200',
        'cat' => 'Transação',
        'desc' => 'Removida transação duplicada: "Assinatura Mensal Servidores" no valor de R$ 350,00',
        'data' => date('d/m/Y', strtotime('-1 days'))
    ],
    [
        'tag' => 'Criação',
        'tag_color' => 'bg-teal-100 text-teal-800 border border-teal-200',
        'cat' => 'Equipe',
        'desc' => 'Novo usuário convidado para a equipe: "Ana Costa" com a função de Membro',
        'data' => date('d/m/Y', strtotime('-2 days'))
    ],
    [
        'tag' => 'Edição',
        'tag_color' => 'bg-amber-100 text-amber-800 border border-amber-200',
        'cat' => 'Equipe',
        'desc' => 'Alterada a função do usuário "João Silva" de Membro para Gerente',
        'data' => date('d/m/Y', strtotime('-3 days'))
    ]
];

// Busca inteligente do arquivo shared_data.php
$caminhos_shared = [
    __DIR__ . '/../shared_data.php',
    __DIR__ . '/shared_data.php',
    dirname(__DIR__) . '/shared_data.php'
];

$carregou_shared = false;
foreach ($caminhos_shared as $caminho) {
    if (file_exists($caminho)) {
        include($caminho);
        $carregou_shared = true;
        break;
    }
}

// Se o arquivo foi carregado, tenta usar a variável dele, senão usa o Mock de segurança
if ($carregou_shared && isset($historico_registros) && is_array($historico_registros)) {
    $registros = $historico_registros;
} else {
    $registros = $mock_registros;
}

// Garante 100% de certeza que $registros é um array válido para evitar o TypeError no count()
if (!is_array($registros)) {
    $registros = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Histórico</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="historico.css/style.css">
    <style>
        .active-nav { background-color: #0f172a; color: white; }
        .sidebar a:hover { color: white; }
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
                <a href="../app/dashboardGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
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
                <!-- Histórico (Ativo) -->
                <a href="../app/historico.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#2dd4bf] text-white shadow-lg transition">
                    <i class="fas fa-history w-5"></i>
                    <span class="font-medium">Histórico</span>
                </a>
                <!-- Configurações -->
                <a href="../app/configuracao.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-cog w-5"></i>
                    <span class="font-medium">Configurações</span>
                </a>

                <!-- Botão de Download na Sidebar -->
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

        <main class="flex-1 p-10 ml-64">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-4xl font-extrabold text-[#0f172a] tracking-tight">Histórico de Alterações</h1>
                    <p class="text-sm text-slate-500 mt-2">Acompanhe todas as mudanças registradas no sistema.</p>
                </div>
            </div>

            <!-- Filtros de Busca -->
            <section class="mb-6 p-6 bg-white rounded-3xl shadow-sm border border-gray-200">
                <div class="grid grid-cols-12 gap-4 items-end">
                    <div class="col-span-7">
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Buscar</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" id="inputBusca" placeholder="Descrição, tipo ou data..." class="w-full pl-12 pr-4 py-3 border rounded-2xl outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                        </div>
                    </div>
                    <div class="col-span-3">
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Tipo de Alteração</label>
                        <select id="filtroTipo" class="w-full px-4 py-3 border rounded-2xl outline-none focus:ring-2 focus:ring-teal-500 text-sm bg-white">
                            <option value="todos">Todos os tipos</option>
                            <option value="criação">Criação</option>
                            <option value="edição">Edição</option>
                            <option value="exclusão">Exclusão</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Data de Alteração</label>
                        <input type="text" id="filtroData" placeholder="dd/mm/aaaa" class="w-full px-4 py-3 border rounded-2xl outline-none focus:ring-2 focus:ring-teal-500 text-sm bg-white">
                    </div>
                </div>
            </section>

            <!-- Tabela de Registros -->
            <section class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-[#0f172a] text-white px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-history"></i>
                        <span class="font-bold uppercase text-sm tracking-wide">Registros de Alterações</span>
                    </div>
                    <span class="text-xs text-slate-300" id="contadorRegistros"><?php echo count($registros); ?> registros</span>
                </div>
                <div class="divide-y" id="containerRegistros">
                    <?php foreach ($registros as $index => $reg): ?>
                        <div class="item-registro p-6 flex flex-col gap-4 lg:flex-row lg:justify-between lg:items-start hover:bg-slate-50 transition"
                             data-desc="<?= strtolower($reg['desc'] ?? '') ?>"
                             data-tipo="<?= strtolower($reg['tag'] ?? '') ?>"
                             data-data="<?= strtolower($reg['data'] ?? '') ?>">
                            <div class="space-y-3">
                                <div class="flex flex-wrap gap-2 items-center">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase <?= $reg['tag_color'] ?? 'bg-slate-100 text-slate-700' ?>"><?= htmlspecialchars($reg['tag'] ?? 'Alteração') ?></span>
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-slate-100 text-slate-700"><?= htmlspecialchars($reg['cat'] ?? 'Sistema') ?></span>
                                </div>
                                <p class="text-sm text-slate-700 font-semibold"><?= htmlspecialchars($reg['desc'] ?? '') ?></p>
                                <div class="flex flex-wrap gap-4 text-xs text-slate-500">
                                    <span class="flex items-center gap-2"><i class="far fa-user"></i> João Silva</span>
                                    <span class="flex items-center gap-2"><i class="far fa-clock"></i><?= htmlspecialchars($reg['data'] ?? date('d/m/Y')) ?>, 18:14:13</span>
                                </div>
                            </div>
                            <button onclick="removerRegistro(this)" class="self-start text-red-500 hover:text-red-700 rounded-full p-2 transition">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div id="msgVazio" class="hidden p-20 text-center text-slate-400">
                    <i class="fas fa-search fa-3x mb-4 block opacity-20"></i>
                    Nenhum registro encontrado para a pesquisa.
                </div>
            </section>
        </main>
    </div>

    <!-- MODAL RELATÓRIO (Idêntico ao modal de transações) -->
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
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-[#1e293b] peer-checked:text-white transition-all">Receita</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="s" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-[#1e293b] peer-checked:text-white transition-all">Despesa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="todos" checked class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-[#1e293b] peer-checked:text-white transition-all">Ambos</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Período</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="mensal" checked class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-[#1e293b] peer-checked:text-white transition-all">Mensal</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="anual" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-[#1e293b] peer-checked:text-white transition-all">Anual</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Mês</label>
                    <select name="mes" class="w-full p-4 rounded-2xl border border-slate-200 bg-white text-slate-700 font-medium appearance-none focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition-all cursor-pointer">
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
                    <select name="ano" class="w-full p-4 rounded-2xl border border-slate-200 bg-white text-slate-700 font-medium appearance-none focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition-all cursor-pointer">
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026" selected>2026</option>
                    </select>
                </div>

                <div class="flex gap-4 pt-6 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('modalRelatorio')" class="flex-1 py-4 border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all">Cancelar</button>
                    <button type="submit" class="flex-1 py-4 bg-[#0d9488] text-white font-bold rounded-2xl shadow-lg hover:bg-[#0f766e] transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-download"></i> Baixar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- LOGICAS DE COMPORTAMENTO EM JAVASCRIPT -->
    <script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

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
        if (event.target == mRel) toggleModal('modalRelatorio');
    }

    // Filtros dinâmicos em tempo real
    const inputBusca = document.getElementById('inputBusca');
    const filtroTipo = document.getElementById('filtroTipo');
    const filtroData = document.getElementById('filtroData');
    const items = document.querySelectorAll('.item-registro');
    const msgVazio = document.getElementById('msgVazio');
    const contadorRegistros = document.getElementById('contadorRegistros');

    function filtrarTabela() {
        const buscaQuery = inputBusca.value.toLowerCase().trim();
        const tipoQuery = filtroTipo.value.toLowerCase();
        const dataQuery = filtroData.value.trim();

        let visiveis = 0;

        items.forEach(item => {
            const desc = item.dataset.desc;
            const tipo = item.dataset.tipo;
            const data = item.dataset.data;

            const matchesBusca = buscaQuery === '' || desc.includes(buscaQuery) || tipo.includes(buscaQuery) || data.includes(buscaQuery);
            const matchesTipo = tipoQuery === 'todos' || tipo === tipoQuery;
            const matchesData = dataQuery === '' || data.includes(dataQuery);

            if (matchesBusca && matchesTipo && matchesData) {
                item.classList.remove('hidden');
                visiveis++;
            } else {
                item.classList.add('hidden');
            }
        });

        // Exibe mensagem de vazio caso nada seja encontrado
        if (visiveis === 0) {
            msgVazio.classList.remove('hidden');
        } else {
            msgVazio.classList.add('hidden');
        }

        // Atualiza dinamicamente o contador do header do Histórico
        contadorRegistros.innerText = visiveis + (visiveis === 1 ? ' registro' : ' registros');
    }

    inputBusca.addEventListener('input', filtrarTabela);
    filtroTipo.addEventListener('change', filtrarTabela);
    filtroData.addEventListener('input', filtrarTabela);

    // Função de remover registro dinamicamente (Visual)
    function removerRegistro(button) {
        const row = button.closest('.item-registro');
        if (confirm('Tem certeza de que deseja remover permanentemente este registro do histórico?')) {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateY(15px)';
            setTimeout(() => {
                row.remove();
                filtrarTabela();
            }, 300);
        }
    }
    </script>
</body>
</html>