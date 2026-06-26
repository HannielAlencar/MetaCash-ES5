<?php
// Inclui a conexão com o banco de dados
require_once '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nome_usuario = $_SESSION['nome_usuario'] ?? 'Admin Master';
$email_usuario = $_SESSION['email_usuario'] ?? 'admin@metacash.com';
$iniciais = strtoupper(substr(trim($nome_usuario), 0, 1));
$pagina_atual = strtolower(basename($_SERVER['PHP_SELF']));
$id_empresa = $_SESSION['id_empresa'] ?? null;


try {
    // Consulta SQL atualizada:
    // Agora vai buscar o e-mail e o nome do Responsável (Gerente) diretamente à tabela de utilizadores.
    $query = "
    SELECT 
        e.id_empresa, -- ADICIONADO AQUI
        e.nome_empresa AS nome, 
        e.cnpj,
        e.status, -- ADICIONADO AQUI
        COALESCE((SELECT email FROM usuarios WHERE id_empresa = e.id_empresa AND nivel_permissao = 'Gerente' LIMIT 1), 'Sem e-mail associado') AS email,
        COALESCE((SELECT nome_completo FROM usuarios WHERE id_empresa = e.id_empresa AND nivel_permissao = 'Gerente' LIMIT 1), 'Sem Responsável') AS responsavel,
        (SELECT COUNT(*) FROM usuarios WHERE id_empresa = e.id_empresa) AS usuarios
    FROM empresas e
";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Busca todos os resultados como um array associativo
    $empresas = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $empresas = [];
    $erro_db = "Erro ao buscar empresas: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash Admin - Gerenciar Empresas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        /* Cores fiéis ao print */
        .bg-sidebar { background-color: #0b192c; }
        .bg-sidebar-darker { background-color: #08111d; }
        .bg-accent { background-color: #3bc191; }
        .text-accent { color: #3bc191; }
        .border-custom-dark { border-color: #1e293b; }
    </style>
</head>
<body class="flex min-h-screen text-slate-800">

    <!-- SIDEBAR -->
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
            <a href="empresasADMIN.php" class="flex items-center gap-3 px-4 py-3 bg-accent text-white rounded-xl shadow-md transition">
                <i class="far fa-building w-5 text-lg"></i>
                <span class="font-semibold text-sm">Empresas</span>
            </a>
            
            <a href="usuariosADMIN.php" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-800/50 hover:text-white rounded-xl transition">
                <i class="fas fa-user-group w-5 text-lg"></i>
                <span class="font-medium text-sm">Usuários</span>
            </a>
        </nav>

        <div class="mt-auto pt-6 border-t border-slate-800 space-y-4 pb-2">
        <a class="bg-[#1e3a5f]/40 p-3 rounded-2xl flex items-center gap-3 border <?= $pagina_atual === 'empresa.php' ? 'border-[#2dd4bf]' : 'border-slate-700/50' ?> hover:bg-[#1e3a5f]/60 transition block group">
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
            <h1 class="text-[32px] font-bold text-[#0b192c] tracking-tight">Gerenciar Empresas</h1>
            <p class="text-slate-500 text-sm mt-1">Visualize e gerencie todas as empresas do sistema</p>
        </header>

        <!-- Barra de Busca -->
        <div class="mb-8">
            <div class="bg-white border-2 border-custom-dark rounded-2xl p-6 shadow-sm">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="inputBusca" placeholder="Buscar por nome, email ou CNPJ..." 
                           class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500 text-sm placeholder:text-slate-400">
                </div>
            </div>
        </div>

        <!-- Tabela -->
        <div class="bg-white border-2 border-custom-dark rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse table-fixed">
                <thead class="bg-sidebar text-white">
                    <tr class="border-b-2 border-custom-dark">
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide w-[28%]">Empresa</th>
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide w-[18%]">CNPJ</th>
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide w-[22%]">Nome do Responsável</th>
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide w-[12%]">Usuários</th>
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide w-[10%]">Status</th>
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide w-[10%]">Ações</th>
                    </tr>
                </thead>

                <!-- Corpo da Tabela -->
                <tbody class="divide-y divide-slate-200" id="tabelaCorpo">
                    <?php foreach ($empresas as $index => $empresa): ?>
                    <tr class="hover:bg-slate-50/50 transition row-empresa" 
                        data-id="<?= $empresa['id_empresa'] ?>" 
                        data-nome="<?= htmlspecialchars(strtolower($empresa['nome'])) ?>"
                        data-email="<?= htmlspecialchars(strtolower($empresa['email'])) ?>" 
                        data-cnpj="<?= htmlspecialchars($empresa['cnpj']) ?>"
                        data-nome-original="<?= htmlspecialchars($empresa['nome']) ?>"
                        data-email-original="<?= htmlspecialchars($empresa['email']) ?>">
                        
                        <td class="py-5 px-6 td-empresa-info">
                            <p class="font-bold text-sm text-[#0b192c] label-nome-empresa">
                                <?= $empresa['status'] === 'Ativa' ? htmlspecialchars($empresa['nome']) : 'Empresa Suspensa' ?>
                            </p>
                            <p class="text-[13px] text-slate-500 mt-0.5 label-email-empresa">
                                <?= htmlspecialchars($empresa['email']) ?>
                            </p>
                        </td>
                        
                        
                        <!-- CNPJ -->
                        <td class="py-5 px-6 text-sm text-slate-600 font-medium">
                            <?= htmlspecialchars($empresa['cnpj']) ?>
                        </td>
                        
                        <!-- Responsável -->
                        <td class="py-5 px-6 text-sm text-slate-600 font-medium">
                            <?= htmlspecialchars($empresa['responsavel']) ?>
                        </td>
                        
                        <!-- Usuários -->
                        <td class="py-5 px-6 text-sm text-slate-600 font-medium">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user-group text-slate-400"></i>
                                <span><?= $empresa['usuarios'] ?></span>
                            </div>
                        </td>
                        
                        <!-- Status -->
                        <td class="py-5 px-6 status-celula">
                            <?php if ($empresa['status'] === 'Ativa'): ?>
                                <span class="badge-status inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-600 border border-emerald-200">
                                    Ativa
                                </span>
                            <?php else: ?>
                                <span class="badge-status inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-500 border border-red-200">
                                    Inativo
                                </span>
                            <?php endif; ?>
                        </td>
                        
                        <!-- Ações -->
                        <td class="py-5 px-6">
                            <div class="flex items-center gap-4">
                                <button onclick="toggleEmpresaStatus(this)" title="Alterar Status" class="btn-status text-lg transition duration-200">
                                    <?php if ($empresa['status'] === 'Ativa'): ?>
                                        <i class="fa-solid fa-ban text-red-500 hover:text-red-700"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-circle-check text-emerald-500 hover:text-emerald-700"></i>
                                    <?php endif; ?>
                                </button>
                                
                                <button onclick="excluirEmpresa(this)" title="Excluir Empresa" class="text-red-500 hover:text-red-700 transition duration-200 text-lg">
                                    <i class="far fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Feedback se busca for vazia -->
            <div id="buscaVazia" class="hidden p-8 text-center text-slate-400 font-medium">
                Nenhuma empresa corresponde aos critérios de pesquisa.
            </div>
        </div>

    </main>

    <!-- POP UP DE CONFIRMAÇÃO DE DESATIVAÇÃO -->
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
                <button id="btnConfirmarAcao" class="flex-1 py-3 text-white font-bold rounded-2xl transition-all text-sm">
                    Sim
                </button>
            </div>
        </div>
    </div>

    <script>
        let acaoAtual = null; // Guarda se é "desativar", "reativar" ou "excluir"
        let elementoAlvo = null; // Guarda a linha da empresa clicada

        // Filtro de Busca
        document.getElementById('inputBusca').addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.row-empresa');
            let encontrouAlguma = false;

            rows.forEach(row => {
                const nome = row.getAttribute('data-nome');
                const email = row.getAttribute('data-email');
                const cnpj = row.getAttribute('data-cnpj');

                if (nome.includes(query) || email.includes(query) || cnpj.includes(query)) {
                    row.classList.remove('hidden');
                    encontrouAlguma = true;
                } else {
                    row.classList.add('hidden');
                }
            });

            const msgVazia = document.getElementById('buscaVazia');
            if (encontrouAlguma) msgVazia.classList.add('hidden');
            else msgVazia.classList.remove('hidden');
        });

        // Função Genérica para Abrir o Pop-up Dinâmico
        function abrirModal(titulo, acao, botao) {
            acaoAtual = acao;
            elementoAlvo = botao;
            document.getElementById('modalTitulo').textContent = titulo;
            
            const btnConfirmar = document.getElementById('btnConfirmarAcao');
            
            // Se for para reativar, o botão SIM fica Verde. Se não, fica Vermelho.
            if(acao === 'reativar') {
                btnConfirmar.className = "flex-1 py-3 text-white font-bold rounded-2xl transition-all text-sm bg-emerald-500 hover:bg-emerald-600 shadow-[0_4px_12px_rgba(16,185,129,0.25)]";
            } else {
                btnConfirmar.className = "flex-1 py-3 text-white font-bold rounded-2xl transition-all text-sm bg-[#ff3b30] hover:bg-[#e03128] shadow-[0_4px_12px_rgba(255,59,48,0.25)]";
            }

            const modal = document.getElementById('modalConfirmacao');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                document.getElementById('modalContent').classList.remove('scale-95');
                document.getElementById('modalContent').classList.add('scale-100');
            }, 10);
        }

        // Função para Fechar o Pop-up
        function fecharModal() {
            const modal = document.getElementById('modalConfirmacao');
            document.getElementById('modalContent').classList.remove('scale-100');
            document.getElementById('modalContent').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                acaoAtual = null;
                elementoAlvo = null;
            }, 150);
        }

        // Fecha ao clicar no fundo preto
        document.getElementById('modalConfirmacao').addEventListener('click', function(e) {
            if (e.target === this) fecharModal();
        });

        // Clique no Botão de Status (Bloqueio / Certinho)
        function toggleEmpresaStatus(button) {
            const row = button.closest('.row-empresa');
            const badge = row.querySelector('.badge-status');
            
            if (badge.textContent.trim() === 'Ativa') {
                abrirModal('Tem certeza que deseja desativar?', 'desativar', button);
            } else {
                abrirModal('Deseja reativar a empresa?', 'reativar', button);
            }
        }

        // Clique no Botão Lixeira (Excluir Empresa)
        function excluirEmpresa(button) {
            const row = button.closest('.row-empresa');
            // Pega o nome original da empresa mesmo que ela esteja suspensa
            const nomeEmpresa = row.getAttribute('data-nome-original'); 
            abrirModal(`Tem certeza que deseja remover a empresa "${nomeEmpresa}" do sistema?`, 'excluir', button);
        }

        // LÓGICA DO BOTÃO "SIM" (Executa a ação de acordo com o que foi clicado)
        document.getElementById('btnConfirmarAcao').addEventListener('click', function() {
            if (!elementoAlvo) return;
            
            const row = elementoAlvo.closest('.row-empresa');
            const idEmpresa = row.getAttribute('data-id');
            const badge = row.querySelector('.badge-status');
            const icon = row.querySelector('.btn-status i');
            const nomeLabel = row.querySelector('.label-nome-empresa');
            const emailLabel = row.querySelector('.label-email-empresa');
            const nomeOriginal = row.getAttribute('data-nome-original');
            const emailOriginal = row.getAttribute('data-email-original');

            // Qual API vai ser chamada?
            const acaoParaBanco = (acaoAtual === 'desativar') ? 'desativar_empresa' : 
                                  (acaoAtual === 'reativar') ? 'ativar_empresa' : 'excluir_empresa';

            // Comunica com o ficheiro acoes_admin.php
            fetch('acoes_admin.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `acao=${acaoParaBanco}&id=${idEmpresa}`
            })
            .then(res => res.json())
            .then(data => {
                if(data.sucesso) {
                    if (acaoAtual === 'desativar') {
                        badge.className = "badge-status inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-500 border border-red-200";
                        badge.textContent = 'Inativo';
                        icon.className = "fa-regular fa-circle-check text-emerald-500 hover:text-emerald-700";
                        nomeLabel.textContent = "Empresa Suspensa";
                        emailLabel.textContent = emailOriginal; 
                        row.setAttribute('data-nome', 'empresa suspensa');
                        row.setAttribute('data-email', emailOriginal.toLowerCase());
                        fecharModal();
                    } 
                    else if (acaoAtual === 'reativar') {
                        badge.className = "badge-status inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-600 border border-emerald-200";
                        badge.textContent = 'Ativa';
                        icon.className = "fa-solid fa-ban text-red-500 hover:text-red-700";
                        nomeLabel.textContent = nomeOriginal;
                        emailLabel.textContent = emailOriginal;
                        row.setAttribute('data-nome', nomeOriginal.toLowerCase());
                        row.setAttribute('data-email', emailOriginal.toLowerCase());
                        fecharModal();
                    } 
                    else if (acaoAtual === 'excluir') {
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        fecharModal();
                        setTimeout(() => {
                            row.remove();
                            const rows = document.querySelectorAll('.row-empresa');
                            if (rows.length === 0) document.getElementById('buscaVazia').classList.remove('hidden');
                        }, 300);
                    }
                } else {
                    alert("Erro ao executar ação: " + data.erro);
                    fecharModal();
                }
            })
            .catch(erro => console.error("Erro na requisição:", erro));
        });
    </script>
   
</body>
</html>