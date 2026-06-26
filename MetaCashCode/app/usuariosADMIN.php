<?php
// Inclui a conexão com o banco de dados
require_once '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nome_usuario = $_SESSION['nome'] ?? 'Admin Master';
$email_usuario = $_SESSION['email'] ?? 'admin@metacash.com';
$iniciais = strtoupper(substr(trim($nome_usuario), 0, 1));
$pagina_atual = strtolower(basename($_SERVER['PHP_SELF']));
$id_empresa = $_SESSION['id_empresa'] ?? null;

try {
    // Consulta SQL adaptada para o seu banco real!
    $query = "SELECT 
            u.id_usuario, 
            u.nome_completo AS nome, 
            u.email, 
            COALESCE(e.nome_empresa, 'Sistema (Sem Empresa)') AS empresa, 
            u.nivel_permissao AS funcao 
          FROM usuarios u 
          LEFT JOIN empresas e ON u.id_empresa = e.id_empresa";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    $usuarios = $stmt->fetchAll(); 
    
} catch (PDOException $e) {
    // CORRIGIDO: Alterado de $empresas para $usuarios
    $usuarios = []; 
    $erro_db = "Erro ao buscar usuários: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash Admin - Gerenciar Usuários</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        /* Cores fiéis à paleta de cores do print */
        .bg-sidebar { background-color: #0b192c; }
        .bg-sidebar-darker { background-color: #08111d; }
        .bg-accent { background-color: #3bc191; }
        .text-accent { color: #3bc191; }
        .border-custom-dark { border-color: #1e293b; }
    </style>
</head>
<body class="flex min-h-screen text-slate-800">

   <aside class="w-64 bg-sidebar text-white flex flex-col fixed h-screen z-40 border-r border-slate-800">
        <div class="flex items-center gap-3 p-6 mb-2 border-b border-slate-700/50">
            <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-shield-halved text-xl"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-lg leading-tight">MetaCash<br>Admin</span>
                <span class="text-[10px] text-slate-400 font-medium mt-0.5">Painel Administrativo</span>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="empresasADMIN.php" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-800/50 hover:text-white rounded-xl transition">
                <i class="far fa-building w-5 text-lg"></i>
                <span class="font-semibold text-sm">Empresas</span>
            </a>
            
            <a href="usuariosADMIN.php" class="flex items-center gap-3 px-4 py-3 bg-accent text-white rounded-xl shadow-md transition">
                <i class="fas fa-user-group w-5 text-lg"></i>
                <span class="font-medium text-sm">Usuários</span>
            </a>
        </nav>

        <div class="mt-auto pt-6 border-t border-slate-800 space-y-4 pb-2">
        <a class="bg-[#1e3a5f]/40 p-3 rounded-2xl flex items-center gap-3 border <?= $pagina_atual === 'usuariosADMIN.php' ? 'border-[#2dd4bf]' : 'border-slate-700/50' ?> hover:bg-[#1e3a5f]/60 transition block group">
            <div class="w-10 h-10 bg-[#2dd4bf] rounded-full flex items-center justify-center text-[#0f172a] font-bold text-lg shrink-0 group-hover:scale-105 transition-transform">
                <?= htmlspecialchars($iniciais, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-sm font-bold truncate text-white"><?= htmlspecialchars($nome_usuario, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="text-[10px] text-gray-400 truncate"><?= htmlspecialchars($email_usuario, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </a>
    </div>

    <div class="border-t border-slate-700 pt-4">
        <form method="POST" action="../auth/logout.php">
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-slate-800 hover:text-red-300 transition text-left">
                <i class="fas fa-sign-out-alt w-5 text-center"></i><span class="font-medium">Sair</span>
            </button>
        </form>
    </div>
</aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="ml-64 flex-1 p-10">
        
        <!-- Cabeçalho -->
        <header class="mb-8">
            <h1 class="text-[32px] font-bold text-[#0b192c] tracking-tight">Gerenciar Usuarios</h1>
            <p class="text-slate-500 text-sm mt-1">Visualize e gerencie todos os usuários do sistema</p>
        </header>

        <!-- Barra de Busca (Idêntica ao layout do print, com borda escura e rounded-2xl) -->
        <div class="mb-8">
            <div class="bg-white border-2 border-custom-dark rounded-2xl p-6 shadow-sm">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="inputBusca" placeholder="Buscar por nome, email ou empresa..." 
                           class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 text-sm placeholder:text-slate-400">
                </div>
            </div>
        </div>

        <!-- Tabela / Grid de Usuários -->
        <div class="bg-white border-2 border-custom-dark rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <!-- Cabeçalho da Tabela -->
                <thead class="bg-sidebar text-white">
                    <tr class="border-b-2 border-custom-dark">
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide">Usuário</th>
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide">Empresa</th>
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide">Funções</th>
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide">Ações</th>
                    </tr>
                </thead>
                <!-- Corpo da Tabela -->
                <tbody class="divide-y divide-slate-200" id="tabelaCorpo">
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr class="hover:bg-slate-50/50 transition row-usuario"
                        data-id="<?= $usuario['id_usuario'] ?>"
                        data-nome="<?= htmlspecialchars(strtolower($usuario['nome'])) ?>"
                        data-email="<?= htmlspecialchars(strtolower($usuario['email'])) ?>" 
                        data-empresa="<?= htmlspecialchars(strtolower($usuario['empresa'])) ?>">
                        
                        <!-- Coluna Usuário (Avatar + Nome e E-mail) -->
                        <td class="py-5 px-6 flex items-center gap-4">
                            <div class="w-11 h-11 bg-teal-500/10 text-teal-600 rounded-full flex items-center justify-center font-bold text-sm shrink-0 border border-teal-500/20">
                                <?= $iniciais; ?>
                            </div>
                            <div>
                                <p class="font-bold text-sm text-[#0b192c]"><?= htmlspecialchars($usuario['nome']) ?></p>
                                <p class="text-[13px] text-slate-500 mt-0.5"><?= htmlspecialchars($usuario['email']) ?></p>
                            </div>
                        </td>
                        
                        <!-- Coluna Empresa -->
                        <td class="py-5 px-6 text-sm text-slate-600 font-medium">
                            <?= htmlspecialchars($usuario['empresa']) ?>
                        </td>
                        
                        <td class="py-5 px-6">
                            <?php if ($usuario['funcao'] === 'Admin'): ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-black text-white shadow-sm">
                                    <i class="fas fa-user-shield text-[10px]"></i>
                                    Admin
                                </span>
                            <?php elseif ($usuario['funcao'] === 'Gerente'): ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#14b8a6] text-white shadow-sm">
                                    <i class="fas fa-shield-halved text-[10px]"></i>
                                    Gerente
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#bae6fd] text-[#0369a1] shadow-sm">
                                    <i class="fas fa-user text-[10px]"></i>
                                    Membro
                                </span>
                            <?php endif; ?>
                        </td>
                        
                        <!-- Coluna Ações (Exclusão) -->
                        <td class="py-5 px-6">
                            <button onclick="excluirUsuario(this)" title="Excluir Usuário" class="text-red-500 hover:text-red-700 transition duration-200 text-lg">
                                <i class="far fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Feedback Visual se a busca não retornar resultados -->
            <div id="buscaVazia" class="hidden p-8 text-center text-slate-400 font-medium">
                Nenhum usuário corresponde aos critérios de pesquisa.
            </div>
        </div>

    </main>

   <div id="modalConfirmacao" class="fixed inset-0 bg-slate-900/40 hidden items-center justify-center z-50 p-4 backdrop-blur-sm">
        <div id="modalContent" class="bg-white rounded-[2rem] w-full max-w-[390px] shadow-2xl overflow-hidden border border-slate-100 transform scale-95 transition-all duration-300">
            <div class="p-8 pb-5 flex justify-between items-start">
                <h3 id="modalTitulo" class="text-[22px] font-bold text-[#0f2440] leading-snug tracking-tight">Tem certeza?</h3>
                <button onclick="fecharModal()" class="text-slate-400 hover:text-slate-600 transition-colors mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="border-b-2 border-[#0b192c] mx-8"></div>
            
            <div class="p-8 pt-6 flex gap-4">
                <button onclick="fecharModal()" class="flex-1 py-3 border-2 border-[#0f2440] text-[#0f2440] font-bold rounded-2xl hover:bg-slate-50 transition-all text-sm">
                    Não
                </button>
                <button id="btnConfirmarAcao" class="flex-1 py-3 bg-[#ff3b30] text-white font-bold rounded-2xl hover:bg-[#e03128] transition-all text-sm shadow-[0_4px_12px_rgba(255,59,48,0.25)]">
                    Sim
                </button>
            </div>
        </div>
    </div>

    <script>
        let elementoAlvo = null;

        // Barra de Busca
        document.getElementById('inputBusca').addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.row-usuario');
            let encontrouAlgum = false;

            rows.forEach(row => {
                const nome = row.getAttribute('data-nome');
                const email = row.getAttribute('data-email');
                const empresa = row.getAttribute('data-empresa');

                if (nome.includes(query) || email.includes(query) || empresa.includes(query)) {
                    row.classList.remove('hidden');
                    encontrouAlgum = true;
                } else {
                    row.classList.add('hidden');
                }
            });

            const msgVazia = document.getElementById('buscaVazia');
            if (encontrouAlgum) msgVazia.classList.add('hidden');
            else msgVazia.classList.remove('hidden');
        });

        // Controles do Pop-up
        function abrirModal(titulo, botao) {
            elementoAlvo = botao;
            document.getElementById('modalTitulo').textContent = titulo;
            const modal = document.getElementById('modalConfirmacao');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                document.getElementById('modalContent').classList.remove('scale-95');
                document.getElementById('modalContent').classList.add('scale-100');
            }, 10);
        }

        function fecharModal() {
            const modal = document.getElementById('modalConfirmacao');
            document.getElementById('modalContent').classList.remove('scale-100');
            document.getElementById('modalContent').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                elementoAlvo = null;
            }, 150);
        }

        document.getElementById('modalConfirmacao').addEventListener('click', function(e) {
            if (e.target === this) fecharModal();
        });

        // Clique no Botão da Lixeira
        function excluirUsuario(button) {
            const row = button.closest('.row-usuario');
            const nomeUsuario = row.querySelector('p').textContent; // Pega o nome do usuário
            abrirModal(`Tem certeza que deseja remover o usuário "${nomeUsuario}" do sistema?`, button);
        }

        // Confirmação para apagar o Usuário
        document.getElementById('btnConfirmarAcao').addEventListener('click', function() {
            if (!elementoAlvo) return;
            
            const row = elementoAlvo.closest('.row-usuario');
            const idUsuario = row.getAttribute('data-id');

            fetch('acoes_admin.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `acao=excluir_usuario&id=${idUsuario}`
            })
            .then(res => res.json())
            .then(data => {
                if(data.sucesso) {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    fecharModal();
                    setTimeout(() => {
                        row.remove();
                        const remainingRows = document.querySelectorAll('.row-usuario');
                        if (remainingRows.length === 0) {
                            document.getElementById('buscaVazia').classList.remove('hidden');
                        }
                    }, 300);
                } else {
                    alert("Erro ao excluir no banco: " + data.erro);
                    fecharModal();
                }
            })
            .catch(erro => console.error("Erro na requisição:", erro));
        });
    </script>
</body>
</html>