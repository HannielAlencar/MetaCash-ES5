<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Trava de segurança corrigida: impede acesso se não estiver logado OU se não possuir o nível exigido
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['nivel_permissao']) || ($_SESSION['nivel_permissao'] !== 'Gerente' && $_SESSION['nivel_permissao'] !== 'Admin')) {
    header("Location: dashboardUsuario.php");
    exit();
}

// Recupera a empresa do usuário logado para listar apenas os membros da mesma organização
$id_empresa_sessao = $_SESSION['id_empresa'] ?? null;
$equipe = [];

try {
    $config_path = __DIR__ . '/../config.php';
    if (file_exists($config_path)) {
        require_once $config_path;
        
        $usuarios_db = [];
        
        // Mantido apenas o PDO para conexão com o Neon (PostgreSQL)
        if (isset($pdo) && $pdo instanceof PDO) {
            if ($id_empresa_sessao) {
                $sql = "SELECT id_usuario, nome_completo, email, nivel_permissao AS cargo FROM usuarios WHERE id_empresa = :id_empresa ORDER BY nome_completo ASC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id_empresa' => $id_empresa_sessao]);
            } else {
                $sql = "SELECT id_usuario, nome_completo, email, nivel_permissao AS cargo FROM usuarios ORDER BY nome_completo ASC";
                $stmt = $pdo->query($sql);
            }
            
            if ($stmt) {
                $usuarios_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        // Se encontrar os registros reais no banco de dados, processa e monta a lista da equipe
        if (!empty($usuarios_db)) {
            foreach ($usuarios_db as $user) {
                $partes_nome = explode(' ', trim($user['nome_completo']));
                $primeiro = $partes_nome[0] ?? '';
                $total_partes = count($partes_nome);
                $ultimo = $total_partes > 1 ? $partes_nome[$total_partes - 1] : '';
                
                $sigla = strtoupper(substr($primeiro, 0, 1) . ($ultimo ? substr($ultimo, 0, 1) : ''));
                
                $equipe[] = [
                    'id_usuario' => $user['id_usuario'],
                    'nome' => $user['nome_completo'],
                    'email' => $user['email'],
                    'cargo' => $user['cargo'] ?? 'Membro',
                    'sigla' => $sigla ?: 'U'
                ];
            }
        }
    }
} catch (Throwable $e) {
    // Agora o erro não é mais 100% silencioso. Ele será salvo nos logs do seu container Docker para facilitar o debug.
    error_log("Erro ao buscar equipe no Neon: " . $e->getMessage());
}

// Dados de fallback locais definidos caso a conexão falhe ou a tabela esteja vazia
if (empty($equipe)) {
    $equipe = [
        ['id_usuario' => 'f1', 'nome' => 'Ana Paula Silva', 'email' => 'ana.silva@empresa.co', 'cargo' => 'Gerente', 'sigla' => 'AP'],
        ['id_usuario' => 'f2', 'nome' => 'Carlos Santos', 'email' => 'carlos.santos@empresa.cc', 'cargo' => 'Gerente', 'sigla' => 'CS'],
        ['id_usuario' => 'f3', 'nome' => 'Mariana Costa', 'email' => 'mariana.costa@empresa.cc', 'cargo' => 'Membro', 'sigla' => 'MC'],
        ['id_usuario' => 'f4', 'nome' => 'Roberto Alves', 'email' => 'roberto.alves@empresa.cc', 'cargo' => 'Gerente', 'sigla' => 'RA'],
        ['id_usuario' => 'f5', 'nome' => 'Juliana Ferreira', 'email' => 'juliana.ferreira@empresa.co', 'cargo' => 'Membro', 'sigla' => 'JF'],
        ['id_usuario' => 'f6', 'nome' => 'Pedro Oliveira', 'email' => 'pedro.oliveira@empresa.co', 'cargo' => 'Membro', 'sigla' => 'PO'],
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipe - MetaCash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
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
                        fundo: 'var(--meta-fundo)',
                    }
                }
            }
        }
    }
    </script>

    <style>
        :root {
            --meta-menu: #0F2440;
            --meta-btn1: #204C73;
            --meta-destaque: #24A6B6;
            --meta-btn2: #35C59A;
            --meta-clara: #5DA4C0;
            --meta-fundo: #FDFEFB;
        }
    </style>

    <script>
        try {
            const temaSalvo = localStorage.getItem('metaCashTheme');
            if (temaSalvo) {
                const cores = JSON.parse(temaSalvo);
                const raiz = document.documentElement;
                if(cores.menu) raiz.style.setProperty('--meta-menu', cores.menu);
                if(cores.btn1) raiz.style.setProperty('--meta-btn1', cores.btn1);
                if(cores.destaque) raiz.style.setProperty('--meta-destaque', cores.destaque);
                if(cores.btn2) raiz.style.setProperty('--meta-btn2', cores.btn2);
                if(cores.clara) raiz.style.setProperty('--meta-clara', cores.clara);
                if(cores.fundo) raiz.style.setProperty('--meta-fundo', cores.fundo);
            }
        } catch (erro) {
            console.error("Erro ao ler localStorage do tema:", erro);
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        
        <?php include_once '../includes/sidebarGerente.php'; ?>

        <main class="flex-1 p-10 ml-64">
            <header class="flex justify-between items-center mb-8 pb-6 border-b border-gray-200">
                <div>
                    <h1 class="text-4xl font-extrabold text-[#0f172a] tracking-tight">Equipe</h1>
                    <p class="text-lg text-[#334155] mt-2">Gerencie os membros e permissões da equipe corporativa</p>
                </div>
                <button onclick="toggleModal('modalMembro')" class="bg-[#2dd4bf] hover:bg-teal-500 text-[#0f172a] px-6 py-3 rounded-xl font-bold transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <i class="fa-solid fa-plus"></i> Adicionar Membro
                </button>
            </header>

            <section class="flex flex-col md:flex-row gap-4 p-4 bg-white rounded-2xl border border-gray-200 shadow-sm items-center mb-8">
                <div class="relative flex-1 w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="inputBusca" placeholder="Buscar por nome ou e-mail..." class="w-full pl-12 pr-4 py-3 rounded-xl border-none bg-gray-50 outline-none focus:ring-2 focus:ring-teal-500 transition text-sm">
                </div>
                <div class="relative w-full md:w-auto">
                    <select id="filtroCargo" class="w-full md:w-48 pl-10 pr-8 py-3 rounded-xl border-none bg-gray-50 appearance-none outline-none focus:ring-2 focus:ring-teal-500 transition cursor-pointer text-sm">
                        <option value="todos">Todos os Níveis</option>
                        <option value="gerente">Gerentes</option>
                        <option value="membro">Membros</option>
                    </select>
                    <i class="fa-solid fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
            </section>

            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gridMembros">
                <?php foreach ($equipe as $membro): ?>
                    <div class="card-membro bg-white p-6 rounded-2xl border border-slate-200 hover:border-[#2dd4bf] hover:shadow-md transition duration-200 flex flex-col justify-between"
                         data-id="<?= htmlspecialchars($membro['id_usuario']) ?>"
                         data-nome="<?= htmlspecialchars(strtolower($membro['nome'])) ?>"
                         data-email="<?= htmlspecialchars(strtolower($membro['email'])) ?>"
                         data-cargo="<?= htmlspecialchars(strtolower(in_array($membro['cargo'], ['Gerente', 'Admin']) ? 'gerente' : 'membro')) ?>">
                        
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center font-bold text-lg border border-teal-100">
                                    <?= htmlspecialchars($membro['sigla']); ?>
                                </div>
                                <div class="overflow-hidden">
                                    <h3 class="font-bold text-slate-800 text-base truncate"><?= htmlspecialchars($membro['nome']); ?></h3>
                                    <p class="text-xs text-slate-400 truncate"><?= htmlspecialchars($membro['email']); ?></p>
                                </div>
                            </div>
                            
                            <div class="relative">
                                <button onclick="toggleDropdown(this, event)" class="text-slate-400 hover:text-slate-600 p-1.5 transition rounded-lg hover:bg-slate-50">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <div class="dropdown-menu hidden absolute right-0 mt-1 w-40 bg-white rounded-xl shadow-xl border border-slate-100 py-1 z-50">
                                    <button onclick="editarMembro('<?= htmlspecialchars($membro['id_usuario'], ENT_QUOTES, 'UTF-8'); ?>', '<?= htmlspecialchars($membro['nome'], ENT_QUOTES, 'UTF-8'); ?>', '<?= htmlspecialchars($membro['cargo'], ENT_QUOTES, 'UTF-8'); ?>')" class="w-full text-left px-4 py-2.5 text-xs text-slate-700 hover:bg-slate-50 font-bold transition flex items-center gap-2 border-b border-slate-100">
                                        <i class="fa-solid fa-user-pen text-[11px] text-teal-600"></i> Editar Cargo
                                    </button>
                                    
                                    <button onclick="removerMembro('<?= htmlspecialchars($membro['id_usuario'], ENT_QUOTES, 'UTF-8'); ?>', '<?= htmlspecialchars($membro['nome'], ENT_QUOTES, 'UTF-8'); ?>')" class="w-full text-left px-4 py-2.5 text-xs text-red-500 hover:bg-red-50 font-bold transition flex items-center gap-2">
                                        <i class="fa-solid fa-trash-can text-[11px]"></i> Remover
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold <?= (in_array($membro['cargo'], ['Gerente', 'Admin'])) ? 'bg-teal-100 text-teal-800' : 'bg-sky-100 text-sky-800'; ?>">
                                <i class="fa-solid <?= (in_array($membro['cargo'], ['Gerente', 'Admin'])) ? 'fa-user-gear' : 'fa-user'; ?> text-[10px]"></i> <?= htmlspecialchars($membro['cargo']); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
            
            <div id="msgVazio" class="hidden p-20 text-center text-slate-400">
                <i class="fas fa-search fa-3x mb-4 block opacity-20"></i>
                Nenhum membro da equipe encontrado para os filtros selecionados.
            </div>
        </main>
    </div>

    <div id="modalMembro" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-[60] p-4 backdrop-blur-sm">
        <div class="bg-white rounded-[2rem] w-full max-w-2xl shadow-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-extrabold text-slate-800">Novo Membro da Equipe</h3>
                <button onclick="toggleModal('modalMembro')" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="../app/gerenciaEquipe.php" method="POST" class="space-y-5">
                <input type="hidden" name="id_empresa" value="<?= htmlspecialchars($id_empresa_sessao ?? '') ?>">
                
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-2 tracking-widest">Nome Completo</label>
                    <input type="text" name="nome_completo" required placeholder="Ex: Guilherme Chedid" class="w-full p-4 rounded-2xl border border-slate-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition-all text-sm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase block mb-2 tracking-widest">Matrícula (Máx. 49 caracteres)</label>
                        <input type="text" name="matricula" maxlength="49" placeholder="Ex: 2026XYZ01" class="w-full p-4 rounded-2xl border border-slate-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition-all text-sm">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase block mb-2 tracking-widest">CPF (Formatado)</label>
                        <input type="text" name="cpf" required maxlength="14" oninput="mascaraCPF(this)" placeholder="000.000.000-00" class="w-full p-4 rounded-2xl border border-slate-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition-all text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase block mb-2 tracking-widest">E-mail Corporativo</label>
                        <input type="email" name="email" required placeholder="Ex: nome@empresa.com" class="w-full p-4 rounded-2xl border border-slate-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition-all text-sm">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase block mb-2 tracking-widest">Senha Provisória (Máx. 8 dig.)</label>
                        <input type="password" name="senha" required maxlength="8" placeholder="No máximo 8 caracteres" class="w-full p-4 rounded-2xl border border-slate-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition-all text-sm">
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-2 tracking-widest">Nível de Permissão (Privilégio)</label>
                    <div class="relative">
                        <select name="nivel_permissao" class="w-full p-4 rounded-2xl border border-slate-200 bg-white text-slate-700 font-medium appearance-none focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition-all cursor-pointer text-sm">
                            <option value="Membro">Membro (Usuário Padrão)</option>
                            <option value="Gerente">Gerente (Gestão Completa corporativa)</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <div class="flex gap-4 pt-4 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('modalMembro')" class="flex-1 py-4 border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all text-sm">Cancelar</button>
                    <button type="submit" class="flex-1 py-4 bg-[#0d9488] text-white font-bold rounded-2xl shadow-lg hover:bg-[#0f766e] transition-all text-sm">Criar Conta</button>
                </div>
            </form>
        </div>
    </div>

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

    <script>
    function editarMembro(id, nome, cargoAtual) {
        const eGerente = (cargoAtual === 'Gerente' || cargoAtual === 'Admin');
        const novoCargo = eGerente ? 'Membro' : 'Gerente';
        const mensagemAcao = eGerente 
            ? `Deseja realmente REBAIXAR o usuário "${nome}" para o nível de Membro (Usuário Padrão)?`
            : `Deseja realmente TORNAR o usuário "${nome}" um Gerente (Gestão Completa)?`;

        if (confirm(mensagemAcao)) {
            window.location.href = `atualizarCargo.php?id=${encodeURIComponent(id)}&novo_cargo=${encodeURIComponent(novoCargo)}`;
        }
    }

    function removerMembro(id, nome) {
        if (confirm(`Deseja realmente REMOVER o usuário "${nome}" da organização?`)) {
            window.location.href = `removerMembro.php?id=${encodeURIComponent(id)}`;
        }
    }

    function mascaraCPF(input) {
        let v = input.value.replace(/\D/g, "");
        v = v.replace(/^(\d{3})(\d)/, "$1.$2");
        v = v.replace(/^(\d{3})\.(\d{3})(\d)/, "$1.$2.$3");
        v = v.replace(/(\d{3})\.(\d{3})\.(\d{3})(\d{1,2})$/, "$1.$2.$3-$4");
        input.value = v;
    }

    function toggleDropdown(button, event) {
        event.stopPropagation();
        const currentMenu = button.nextElementSibling;
        
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== currentMenu) {
                menu.classList.add('hidden');
            }
        });

        currentMenu.classList.toggle('hidden');
    }

    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

    window.onclick = function(event) {
        const mRel = document.getElementById('modalRelatorio');
        const mMemb = document.getElementById('modalMembro');
        if (event.target == mRel) toggleModal('modalRelatorio');
        if (event.target == mMemb) toggleModal('modalMembro');

        if (!event.target.closest('.dropdown-menu') && !event.target.closest('button[onclick^="toggleDropdown"]')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    }

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
            const email = card.dataset.email;
            const cargoCard = card.dataset.cargo;

            const matchesBusca = query === '' || nome.includes(query) || email.includes(query);
            
            let matchesCargo = false;
            if (cargo === 'todos') {
                matchesCargo = true;
            } else if (cargo === 'gerente') {
                matchesCargo = (cargoCard === 'gerente');
            } else if (cargo === 'membro') {
                matchesCargo = (cargoCard === 'membro');
            }

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