<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteção com try-catch contra falhas de conexão ou variáveis de ambiente ausentes no config.php
try {
    $config_path = __DIR__ . '/../config.php';
    if (file_exists($config_path)) {
        require_once $config_path;
    }
} catch (Throwable $e) {
    // Captura silenciosamente o erro de banco de dados para evitar quebras de tela
    // O sistema usará os dados de fallback locais definidos abaixo
}

// Trava de segurança tratada para o ambiente local
if (isset($_SESSION['id_usuario'])) {
    if ($_SESSION['nivel_permissao'] !== 'Gerente' && $_SESSION['nivel_permissao'] !== 'Admin') {
        header("Location: dashboardUsuario.php");
        exit();
    }
}

// Se os dados não forem carregados do config.php ou vierem vazios, define a equipe mock de fallback
if (!isset($equipe) || !is_array($equipe) || empty($equipe)) {
    $equipe = [
        ['nome' => 'Ana Paula Silva', 'email' => 'ana.silva@empresa.co', 'cargo' => 'Gerente', 'sigla' => 'AP'],
        ['nome' => 'Carlos Santos', 'email' => 'carlos.santos@empresa.cc', 'cargo' => 'Gerente', 'sigla' => 'CS'],
        ['nome' => 'Mariana Costa', 'email' => 'mariana.costa@empresa.cc', 'cargo' => 'Membro', 'sigla' => 'MC'],
        ['nome' => 'Roberto Alves', 'email' => 'roberto.alves@empresa.cc', 'cargo' => 'Gerente', 'sigla' => 'RA'],
        ['nome' => 'Juliana Ferreira', 'email' => 'juliana.ferreira@empresa.co', 'cargo' => 'Membro', 'sigla' => 'JF'],
        ['nome' => 'Pedro Oliveira', 'email' => 'pedro.oliveira@empresa.co', 'cargo' => 'Membro', 'sigla' => 'PO'],
        ['nome' => 'Juandir Alves', 'email' => 'juandir.alves@empresa.com', 'cargo' => 'Gerente', 'sigla' => 'JA'],
        ['nome' => 'Claudia Ferreira', 'email' => 'claudia.ferreia@empresa.co', 'cargo' => 'Membro', 'sigla' => 'CF'],
        ['nome' => 'Fernando Dolores', 'email' => 'fernando.dolores@empresa.com', 'cargo' => 'Membro', 'sigla' => 'FD'],
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Equipe</title>
    <link rel="stylesheet" href="../assets/css/gerenciaEquipe.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos de compatibilidade e fallback caso o CSS externo falhe */
        body { font-family: 'Inter', sans-serif; }
        .active-nav { background-color: #2dd4bf; color: white; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        
        <!-- SIDEBAR FIXA E SINCRONIZADA -->
        <aside class="w-64 bg-[#0f172a] text-white p-4 flex flex-col fixed h-screen shrink-0 z-40">
            <div class="flex items-center gap-3 mb-10 px-2 pt-2">
                <!-- LOGO COM PROTEÇÃO CONTRA LOOP DE ERRO -->
                <img src="../assets/img/logo_empresas.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover" onerror="this.onerror=null; this.src='../DashboardGerente/image_75793b.png'; this.onerror=function(){this.src='https://ui-avatars.com/api/?name=MetaCash&background=2dd4bf&color=0f172a';}">
                <div class="flex flex-col">
                    <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
                    <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Gestão Empresarial</span>
                </div>
            </div>

            <!-- Navegação principal com abas sincronizadas -->
            <nav class="flex-1 space-y-2 text-sm">
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
                <!-- Equipe (Ativo) -->
                <a href="../app/gerenciaEquipe.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#2dd4bf] text-white shadow-lg transition font-semibold">
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
            <div class="mt-auto pt-6 border-t border-slate-800 space-y-4 pb-2 text-sm">
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

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="flex-1 p-10 ml-64">
            <header class="flex justify-between items-center mb-8 pb-6 border-b border-gray-200">
                <div>
                    <h1 class="text-4xl font-extrabold text-[#0f172a] tracking-tight">Equipe</h1>
                    <p class="text-lg text-[#334155] mt-2">Gerencie os membros e permissões da equipe</p>
                </div>
                <button class="bg-[#2dd4bf] hover:bg-teal-500 text-[#0f172a] px-6 py-3 rounded-xl font-bold transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <i class="fa-solid fa-plus"></i> Adicionar Membro
                </button>
            </header>

            <!-- Barra de Filtros -->
            <section class="flex flex-col md:flex-row gap-4 p-4 bg-white rounded-2xl border border-gray-200 shadow-sm items-center mb-8">
                <div class="relative flex-1 w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="inputBusca" placeholder="Buscar por nome ou e-mail..." class="w-full pl-12 pr-4 py-3 rounded-xl border-none bg-gray-50 outline-none focus:ring-2 focus:ring-teal-500 transition text-sm">
                </div>
                <div class="relative w-full md:w-auto">
                    <select id="filtroCargo" class="w-full md:w-48 pl-10 pr-8 py-3 rounded-xl border-none bg-gray-50 appearance-none outline-none focus:ring-2 focus:ring-teal-500 transition cursor-pointer text-sm">
                        <option value="todos">Todos os Cargos</option>
                        <option value="Gerente">Gerente</option>
                        <option value="Membro">Membro</option>
                    </select>
                    <i class="fa-solid fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
            </section>

            <!-- Grid de Membros -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gridMembros">
                <?php foreach ($equipe as $membro): ?>
                    <div class="card-membro bg-white p-6 rounded-2xl border border-slate-200 hover:border-[#2dd4bf] hover:shadow-md transition duration-200 flex flex-col justify-between"
                         data-nome="<?= htmlspecialchars(strtolower($membro['nome'])) ?>"
                         data-cargo="<?= htmlspecialchars(strtolower($membro['cargo'])) ?>">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center font-bold text-lg border border-teal-100">
                                    <?php echo $membro['sigla']; ?>
                                </div>
                                <div class="overflow-hidden">
                                    <h3 class="font-bold text-slate-800 text-base truncate"><?php echo $membro['nome']; ?></h3>
                                    <p class="text-xs text-slate-400 truncate"><?php echo $membro['email']; ?></p>
                                </div>
                            </div>
                            <button class="text-slate-400 hover:text-slate-600 p-1.5 transition">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold <?php echo (strtolower($membro['cargo']) == 'gerente') ? 'bg-teal-100 text-teal-800' : 'bg-sky-100 text-sky-800'; ?>">
                                <i class="fa-solid fa-user-gear text-[10px]"></i> <?php echo $membro['cargo']; ?>
                            </span>
                            <button class="text-xs text-red-500 hover:text-red-700 font-bold transition">Remover</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
            
            <div id="msgVazio" class="hidden p-20 text-center text-slate-400">
                <i class="fas fa-search fa-3x mb-4 block opacity-20"></i>
                Nenhum membro da equipe encontrado.
            </div>
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
            
            <form action="../app/gerarPDF.php" method="GET" target="_blank" class="space-y-6">
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

    

    <!-- LOGICAS DE FILTRO E COMPORTAMENTO -->
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

    // Filtros em tempo real
    const inputBusca = document.getElementById('inputBusca');
    const filtroCargo = document.getElementById('filtroCargo');
    const cards = document.querySelectorAll('.card-membro');
    const msgVazio = document.getElementById('msgVazio');

    function filtrarEquipe() {
        const query = inputBusca.value.toLowerCase().trim();
        const cargo = filtroCargo.value.toLowerCase();
        let visiveis = 0;

        cards.forEach(card => {
            const nome = card.dataset.nome;
            const cargoCard = card.dataset.cargo;

            const matchesBusca = query === '' || nome.includes(query);
            const matchesCargo = cargo === 'todos' || cargoCard === cargo;

            if (matchesBusca && matchesCargo) {
                card.classList.remove('hidden');
                visiveis++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (visiveis === 0) {
            msgVazio.classList.remove('hidden');
        } else {
            msgVazio.classList.add('hidden');
        }
    }

    inputBusca.addEventListener('input', filtrarEquipe);
    filtroCargo.addEventListener('change', filtrarEquipe);
    </script>
</body>
</html>