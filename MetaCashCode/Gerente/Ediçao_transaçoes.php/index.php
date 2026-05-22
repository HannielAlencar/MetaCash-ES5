<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Editor: Transações</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
        .sidebar { background-color: #0F172A; }
    </style>
</head>
<body class="flex min-h-screen">

    <!-- SIDEBAR FIXA -->
        <aside class="w-64 bg-[#0f172a] text-white p-4 flex flex-col fixed h-screen shrink-0 z-40">
            <div class="flex items-center gap-3 mb-10 px-2 pt-2">
                <!-- LOGO COM PROTEÇÃO CONTRA LOOP DE ERRO -->
                <img src="/MetaCashCode/Usuario/Dashboard/img/logo empresas.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover" onerror="this.onerror=null; this.src='../DashboardGerente/image_75793b.png'; this.onerror=function(){this.src='https://ui-avatars.com/api/?name=MetaCash&background=2dd4bf&color=0f172a';}">
                <div class="flex flex-col">
                    <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
                    <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Gestão Empresarial</span>
                </div>
            </div>

            <!-- Navegação principal com fonte e tamanho sincronizados com o Dashboard de Usuário -->
            <nav class="flex-1 space-y-2">
                <!-- Dashboard -->
                <a href="../DashboardGerente/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-th-large w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <!-- Transações -->
                <a href="../TransaçoesGerente.php/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-exchange-alt w-5"></i>
                    <span class="font-medium">Transações</span>
                </a>
                <!-- Equipe -->
                <a href="../Gerencia_de_equipe.php/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-users w-5"></i>
                    <span class="font-medium">Equipe</span>
                </a>
                <!-- Gerenciar Páginas -->
                <a href="../Gerencia_de_paginas.php/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#2dd4bf] text-white shadow-lg transition">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="font-medium">Gerenciar Páginas</span>
                </a>
                <!-- Histórico -->
                <a href="../Historico.php/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-history w-5"></i>
                    <span class="font-medium">Histórico</span>
                </a>
                <!-- Configurações -->
                <a href="../configuraçao.php/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
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
                <a href="../PerfilGerente.php/index.php" class="bg-[#1e3a5f]/40 p-3 rounded-2xl flex items-center gap-3 border border-slate-700/50 hover:bg-[#1e3a5f]/60 transition block group">
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

    <!-- Conteúdo Principal -->
    <main class="flex-1 ml-64 p-8">
        <header class="mb-6">
            <a href="../Gerencia_de_paginas.php/index.php" class="text-sm text-teal-600 font-semibold mb-2 block"><i class="fas fa-arrow-left mr-2"></i> Voltar ao Gerenciamento</a>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-teal-600 flex items-center justify-center rounded-lg text-white"><i class="fas fa-exchange-alt"></i></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Editor: Transações</h2>
                    <p class="text-slate-500">Personalize a aparência e funcionalidades desta página</p>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-3 gap-6">
            <!-- Coluna da Esquerda (Config e Textos) -->
            <div class="col-span-2 space-y-6">
                <!-- Configurações Gerais -->
                <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-800 mb-4 flex items-center"><i class="fas fa-cog mr-2 text-slate-400"></i> Configurações Gerais</h4>
                    <label class="text-xs font-bold text-slate-500 uppercase">Fonte</label>
                    <select class="w-full border rounded-lg p-3 mt-1 mb-4 bg-white"><option>Inter</option></select>
                    <p class="text-sm text-slate-400 mb-4">Prévia: Texto de exemplo com a fonte selecionada</p>
                    
                    <label class="text-xs font-bold text-slate-500 uppercase">Tamanho da Fonte</label>
                    <div class="grid grid-cols-2 gap-4 mt-1">
                        <button class="border py-3 rounded-lg font-medium text-slate-600">Pequeno</button>
                        <button class="bg-teal-500 text-white py-3 rounded-lg font-medium">Médio</button>
                        <button class="border py-3 rounded-lg font-medium text-slate-600">Grande</button>
                        <button class="border py-3 rounded-lg font-medium text-slate-600">Extra Grande</button>
                    </div>
                    <p class="text-sm text-slate-400 mt-4">Prévia: Este é um exemplo de texto</p>
                </section>

                <!-- Textos Personalizados -->
                <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-800 mb-6 flex items-center"><i class="fas fa-pen mr-2 text-slate-400"></i> Textos Personalizados</h4>
                    <div class="space-y-6">
                        <div><label class="text-xs font-bold text-slate-500 uppercase">Título da Página</label><input type="text" value="Transações" class="w-full border rounded-lg p-3 mt-1"><p class="text-[10px] text-slate-400 text-right mt-1">10/50</p></div>
                        <div><label class="text-xs font-bold text-slate-500 uppercase">Subtítulo da Página</label><input type="text" value="Gerencie todas as receitas e despesas da empresa" class="w-full border rounded-lg p-3 mt-1"><p class="text-[10px] text-slate-400 text-right mt-1">46/100</p></div>
                        <div><label class="text-xs font-bold text-slate-500 uppercase">Texto do Botão Adicionar</label><input type="text" value="Nova Transação" class="w-full border rounded-lg p-3 mt-1"><p class="text-[10px] text-slate-400 text-right mt-1">14/30</p></div>
                        <div><label class="text-xs font-bold text-slate-500 uppercase">Texto de Busca (Placeholder)</label><input type="text" value="Buscar transações..." class="w-full border rounded-lg p-3 mt-1"><p class="text-[10px] text-slate-400 text-right mt-1">20/50</p></div>
                        <div><label class="text-xs font-bold text-slate-500 uppercase">Mensagem Quando Vazio</label><input type="text" value="Nenhuma transação encontrada" class="w-full border rounded-lg p-3 mt-1"><p class="text-[10px] text-slate-400 text-right mt-1">28/60</p></div>
                    </div>
                    <p class="text-xs text-slate-400 mt-4 italic"><i class="fas fa-lightbulb mr-1"></i> Personalize os textos para adequar ao seu contexto de negócio</p>
                </section>
            </div>

            <!-- Coluna da Direita (Colunas Visíveis) -->
            <div class="col-span-1">
                <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-800 mb-6">Colunas Visíveis (5/6)</h4>
                    <div class="space-y-3">
                        <div class="border rounded-xl p-3 flex justify-between items-center"><div class="flex items-center gap-3"><i class="fas fa-chart-line text-teal-600"></i><div><p class="font-bold text-sm">Card de Receitas</p><p class="text-xs text-slate-400">Tamanho: pequeno</p></div></div><div class="flex gap-2 text-slate-400"><i class="fas fa-pen text-xs cursor-pointer"></i><i class="fas fa-eye text-xs cursor-pointer"></i></div></div>
                        <div class="border rounded-xl p-3 flex justify-between items-center"><div class="flex items-center gap-3"><i class="fas fa-chart-area text-teal-600"></i><div><p class="font-bold text-sm">Card de Despesas</p><p class="text-xs text-slate-400">Tamanho: pequeno</p></div></div><div class="flex gap-2 text-slate-400"><i class="fas fa-pen text-xs cursor-pointer"></i><i class="fas fa-eye text-xs cursor-pointer"></i></div></div>
                        <div class="border rounded-xl p-3 flex justify-between items-center"><div class="flex items-center gap-3"><i class="fas fa-dollar-sign text-teal-600"></i><div><p class="font-bold text-sm">Card de Saldo</p><p class="text-xs text-slate-400">Tamanho: pequeno</p></div></div><div class="flex gap-2 text-slate-400"><i class="fas fa-pen text-xs cursor-pointer"></i><i class="fas fa-eye text-xs cursor-pointer"></i></div></div>
                        <div class="border rounded-xl p-3 flex justify-between items-center bg-gray-50"><div class="flex items-center gap-3"><i class="fas fa-list text-slate-400"></i><div><p class="font-bold text-sm text-slate-500">Lista de Transações</p><p class="text-xs text-slate-400">Tamanho: médio</p></div></div><div class="flex gap-2 text-slate-400"><i class="fas fa-pen text-xs cursor-pointer"></i><i class="fas fa-eye-slash text-xs cursor-pointer"></i></div></div>
                    </div>
                </section>

                <div class="mt-6 space-y-3">
                    <button class="w-full bg-slate-900 text-white py-3 rounded-lg font-bold hover:bg-slate-800"><i class="fas fa-save mr-2"></i> Salvar Alterações</button>
                    <button class="w-full bg-white text-red-500 border border-red-200 py-3 rounded-lg font-bold hover:bg-red-50"><i class="fas fa-trash-alt mr-2"></i> Restaurar Padrão</button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

<script>

function toggleRelatorioModal() {
            const modal = document.getElementById('modalRelatorio');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
}
</script>