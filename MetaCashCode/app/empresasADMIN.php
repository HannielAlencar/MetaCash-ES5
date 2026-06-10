<?php
// Simulação de banco de dados baseado exatamente nos dados do print
$empresas = [
    [
        'nome' => 'Tech Solutions LTDA',
        'email' => 'contato@techsolutions.com',
        'cnpj' => '12.345.678/0001-90',
        'responsavel' => 'Fernando Alves Almeida Costa',
        'usuarios' => 15,
        'status' => 'Ativa'
    ],
    [
        'nome' => 'Comércio Brasil',
        'email' => 'financeiro@comerciobrasil.com',
        'cnpj' => '98.765.432/0001-10',
        'responsavel' => 'Lucas Oliveira Duraes Ferreira',
        'usuarios' => 8,
        'status' => 'Ativa'
    ],
    [
        'nome' => 'Startup Inovação',
        'email' => 'admin@startupinovacao.com',
        'cnpj' => '11.222.333/0001-44',
        'responsavel' => 'Roberta Maria Soares Machado',
        'usuarios' => 3,
        'status' => 'Inativo'
    ],
    [
        'nome' => 'Empresa Suspensa',
        'email' => 'contato@suspensa.com',
        'cnpj' => '55.666.777/0001-88',
        'responsavel' => 'Amanda Paula Souza Pereira',
        'usuarios' => 12,
        'status' => 'Inativo'
    ]
];
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
        <!-- Logo -->
        <div class="flex items-center gap-3 p-6 mb-2 border-b border-slate-700/50">
            <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-shield-halved text-xl"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-lg leading-tight">MetaCash<br>Admin</span>
                <span class="text-[10px] text-slate-400 font-medium mt-0.5">Painel Administrativo</span>
            </div>
        </div>

        <!-- Navegação -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <!-- Item ativo -->
            <a href="empresasADMIN.php" class="flex items-center gap-3 px-4 py-3 bg-accent text-white rounded-xl shadow-md transition">
                <i class="far fa-building w-5 text-lg"></i>
                <span class="font-semibold text-sm">Empresas</span>
            </a>
            
            <!-- Item inativo -->
            <a href="usuariosADMIN.php" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-800/50 hover:text-white rounded-xl transition">
                <i class="fas fa-user-group w-5 text-lg"></i>
                <span class="font-medium text-sm">Usuários</span>
            </a>
        </nav>

        <!-- Perfil e Sair -->
        <div class="p-4 border-t border-slate-700/50">
            <div class="bg-sidebar-darker p-3 rounded-xl flex items-center gap-3 mb-4 border border-slate-700/50">
                <div class="w-9 h-9 bg-accent rounded-full flex items-center justify-center text-white shrink-0">
                    <i class="fas fa-shield-halved text-sm"></i>
                </div>
                <div class="flex flex-col overflow-hidden">
                    <span class="text-sm font-bold truncate text-white">Admin Master</span>
                    <span class="text-[11px] text-slate-400 truncate">admin@metacash.com</span>
                </div>
            </div>
            <a href="../auth/loginADMIN.php" class="flex items-center gap-3 px-4 py-2 text-slate-400 hover:text-white transition">
                <i class="fas fa-arrow-right-from-bracket w-5"></i>
                <span class="font-medium text-sm">Sair</span>
            </a>
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
            <table class="w-full text-left border-collapse">
                <!-- Cabeçalho da Tabela -->
                <thead class="bg-sidebar text-white">
                    <tr class="border-b-2 border-custom-dark">
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide">Empresa</th>
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide">CNPJ</th>
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide">Nome do Responsável</th>
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide">Usuários</th>
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide">Status</th>
                        <th class="py-4 px-6 text-sm font-semibold tracking-wide">Ações</th>
                    </tr>
                </thead>
                <!-- Corpo da Tabela -->
                <tbody class="divide-y divide-slate-200" id="tabelaCorpo">
                    <?php foreach ($empresas as $index => $empresa): ?>
                    <tr class="hover:bg-slate-50/50 transition row-empresa" 
                        data-nome="<?= htmlspecialchars(strtolower($empresa['nome'])) ?>" 
                        data-email="<?= htmlspecialchars(strtolower($empresa['email'])) ?>" 
                        data-cnpj="<?= htmlspecialchars($empresa['cnpj']) ?>"
                        data-nome-original="<?= htmlspecialchars($empresa['nome']) ?>"
                        data-email-original="<?= htmlspecialchars($empresa['email']) ?>">
                        
                        <!-- Empresa Info -->
                        <td class="py-5 px-6 td-empresa-info">
                            <p class="font-bold text-sm text-[#0b192c] label-nome-empresa"><?= htmlspecialchars($empresa['nome']) ?></p>
                            <p class="text-[13px] text-slate-500 mt-0.5 label-email-empresa"><?= htmlspecialchars($empresa['email']) ?></p>
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

    <!-- POP UP DE CONFIRMAÇÃO DE DESATIVAÇÃO (Exatamente igual ao print) -->
    <div id="modalDesativar" class="fixed inset-0 bg-slate-900/40 hidden items-center justify-center z-50 p-4 backdrop-blur-sm">
        <div class="bg-white rounded-[2rem] w-full max-w-[390px] shadow-2xl overflow-hidden border border-slate-100 transform scale-95 transition-all duration-300">
            <!-- Header com título e fechar -->
            <div class="p-8 pb-5 flex justify-between items-start">
                <h3 class="text-[22px] font-bold text-[#0f2440] leading-snug tracking-tight">Tem certeza que deseja desativar?</h3>
                <button onclick="fecharModalDesativar()" class="text-slate-400 hover:text-slate-600 transition-colors mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Divisor azul escuro fiel ao print -->
            <div class="border-b-2 border-[#0b192c] mx-8"></div>
            
            <!-- Ações -->
            <div class="p-8 pt-6 flex gap-4">
                <button onclick="fecharModalDesativar()" class="flex-1 py-3 border-2 border-[#0f2440] text-[#0f2440] font-bold rounded-2xl hover:bg-slate-50 transition-all text-sm">
                    Não
                </button>
                <button id="btnConfirmarDesativar" class="flex-1 py-3 bg-[#ff3b30] text-white font-bold rounded-2xl hover:bg-[#e03128] transition-all text-sm shadow-[0_4px_12px_rgba(255,59,48,0.25)]">
                    Sim
                </button>
            </div>
        </div>
    </div>

    <!-- LOGICA INTERATIVA CLIENT-SIDE (JS) -->
    <script>
        let empresaParaDesativar = null;

        // Função para realizar filtro instantâneo ao digitar na barra de busca
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

            // Gerencia feedback visual de pesquisa sem resultados
            const msgVazia = document.getElementById('buscaVazia');
            if (encontrouAlguma) {
                msgVazia.classList.add('hidden');
            } else {
                msgVazia.classList.remove('hidden');
            }
        });

        // Alternador de Status Dinâmico (Chama o modal de desativação ou ativa novamente)
        function toggleEmpresaStatus(button) {
            const row = button.closest('.row-empresa');
            const badge = row.querySelector('.badge-status');
            const nomeLabel = row.querySelector('.label-nome-empresa');
            const emailLabel = row.querySelector('.label-email-empresa');
            const nomeOriginal = row.getAttribute('data-nome-original');
            const emailOriginal = row.getAttribute('data-email-original');

            if (badge.textContent.trim() === 'Ativa') {
                // Configura referência e abre o modal de desativação idêntico ao do print
                empresaParaDesativar = button;
                abrirModalDesativar();
            } else {
                // Ativa novamente de forma direta (clicou no certinho)
                badge.className = "badge-status inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-600 border border-emerald-200";
                badge.textContent = 'Ativa';
                button.querySelector('i').className = "fa-solid fa-ban text-red-500 hover:text-red-700";
                
                // NOVO REQUISITO: Restaura os dados originais ao clicar no certinho de reativação
                nomeLabel.textContent = nomeOriginal;
                emailLabel.textContent = emailOriginal;

                // Atualiza também os atributos de busca para coincidir com os dados originais restaurados
                row.setAttribute('data-nome', nomeOriginal.toLowerCase());
                row.setAttribute('data-email', emailOriginal.toLowerCase());
            }
        }

        // Funções de Controle do Modal de Desativação
        function abrirModalDesativar() {
            const modal = document.getElementById('modalDesativar');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Pequeno delay para animação de scale
            setTimeout(() => {
                modal.firstElementChild.classList.remove('scale-95');
                modal.firstElementChild.classList.add('scale-100');
            }, 10);
        }

        // Fecha o modal de desativação
        function fecharModalDesativar() {
            const modal = document.getElementById('modalDesativar');
            modal.firstElementChild.classList.remove('scale-100');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                empresaParaDesativar = null;
            }, 150);
        }

        // Listener do botão de confirmação dentro do Modal
        document.getElementById('btnConfirmarDesativar').addEventListener('click', function() {
            if (empresaParaDesativar) {
                const row = empresaParaDesativar.closest('.row-empresa');
                const badge = row.querySelector('.badge-status');
                const icon = empresaParaDesativar.querySelector('i');
                const nomeLabel = row.querySelector('.label-nome-empresa');
                const emailLabel = row.querySelector('.label-email-empresa');

                // Executa a ação de desativar (Altera visualmente para Inativo)
                badge.className = "badge-status inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-500 border border-red-200";
                badge.textContent = 'Inativo';
                icon.className = "fa-regular fa-circle-check text-emerald-500 hover:text-emerald-700";

                // REQUISITO ANTERIOR: Altera os dados exibidos da empresa para os valores suspensos requisitados
                nomeLabel.textContent = "Empresa Suspensa";
                emailLabel.textContent = "contato@suspensa.com";

                // Atualiza também os atributos de busca para coincidir com a alteração
                row.setAttribute('data-nome', 'empresa suspensa');
                row.setAttribute('data-email', 'contato@suspensa.com');

                fecharModalDesativar();
            }
        });

        // Fecha modal ao clicar no fundo
        document.getElementById('modalDesativar').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModalDesativar();
            }
        });

        // Função para Excluir Empresa (Simulação)
        function excluirEmpresa(button) {
            const row = button.closest('.row-empresa');
            const nomeEmpresa = row.querySelector('.label-nome-empresa').textContent;
            
            if (confirm(`Deseja realmente remover a empresa "${nomeEmpresa}" do painel?`)) {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    // Atualiza a validação da tabela vazia
                    const rows = document.querySelectorAll('.row-empresa');
                    if (rows.length === 0) {
                        document.getElementById('buscaVazia').classList.remove('hidden');
                    }
                }, 300);
            }
        }
    </script>
</body>
</html>