<?php
// Simulação de banco de dados baseado exatamente nos dados do print fornecido
$usuarios = [
    [
        'nome' => 'João Silva',
        'email' => 'joao.silva@techsolutions.com',
        'empresa' => 'Tech Solutions LTDA',
        'funcao' => 'Gerente'
    ],
    [
        'nome' => 'Maria Santos',
        'email' => 'maria.santos@comerciobrasil.com',
        'empresa' => 'Comércio Brasil',
        'funcao' => 'Gerente'
    ],
    [
        'nome' => 'Pedro Oliveira',
        'email' => 'pedro@startupinovacao.com',
        'empresa' => 'Startup Inovação',
        'funcao' => 'Gerente'
    ],
    [
        'nome' => 'Ana Costa',
        'email' => 'ana.costa@techsolutions.com',
        'empresa' => 'Tech Solutions LTDA',
        'funcao' => 'Membro'
    ]
];

// Função auxiliar para obter as iniciais de cada nome
function obterIniciais($nome) {
    $palavras = explode(' ', $nome);
    $iniciais = '';
    if (isset($palavras[0])) {
        $iniciais .= mb_substr($palavras[0], 0, 1, 'UTF-8');
    }
    if (isset($palavras[1])) {
        $iniciais .= mb_substr($palavras[1], 0, 1, 'UTF-8');
    }
    return mb_strtoupper($iniciais, 'UTF-8');
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
            <!-- Item inativo -->
            <a href="empresasADMIN.php" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-800/50 hover:text-white rounded-xl transition">
                <i class="far fa-building w-5 text-lg"></i>
                <span class="font-semibold text-sm">Empresas</span>
            </a>
            
            <!-- Item Ativo -->
            <a href="usuariosADMIN.php" class="flex items-center gap-3 px-4 py-3 bg-accent text-white rounded-xl shadow-md transition">
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
                        data-nome="<?= htmlspecialchars(strtolower($usuario['nome'])) ?>" 
                        data-email="<?= htmlspecialchars(strtolower($usuario['email'])) ?>" 
                        data-empresa="<?= htmlspecialchars(strtolower($usuario['empresa'])) ?>">
                        
                        <!-- Coluna Usuário (Avatar + Nome e E-mail) -->
                        <td class="py-5 px-6 flex items-center gap-4">
                            <div class="w-11 h-11 bg-teal-500/10 text-teal-600 rounded-full flex items-center justify-center font-bold text-sm shrink-0 border border-teal-500/20">
                                <?= obterIniciais($usuario['nome']) ?>
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
                        
                        <!-- Coluna Funções (Badges Dinâmicos com ícones idênticos) -->
                        <td class="py-5 px-6">
                            <?php if ($usuario['funcao'] === 'Gerente'): ?>
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

    <!-- LOGICA INTERATIVA CLIENT-SIDE (JS) -->
    <script>
        // Função de filtro em tempo real ao digitar na barra de busca
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
                    encontrouAlguma = true;
                } else {
                    row.classList.add('hidden');
                }
            });

            // Mostra ou esconde mensagem de feedback para tabela sem resultados
            const msgVazia = document.getElementById('buscaVazia');
            if (encontrouAlguma) {
                msgVazia.classList.add('hidden');
            } else {
                msgVazia.classList.remove('hidden');
            }
        });

        // Função para remover usuário dinamicamente da lista
        function excluirUsuario(button) {
            const row = button.closest('.row-usuario');
            const nomeUsuario = row.querySelector('p').textContent;
            
            if (confirm(`Tem certeza que deseja remover o usuário "${nomeUsuario}" do sistema?`)) {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    
                    // Valida se a tabela inteira ficou vazia após exclusão
                    const remainingRows = document.querySelectorAll('.row-usuario');
                    if (remainingRows.length === 0) {
                        document.getElementById('buscaVazia').classList.remove('hidden');
                    }
                }, 300);
            }
        }
    </script>
</body>
</html>