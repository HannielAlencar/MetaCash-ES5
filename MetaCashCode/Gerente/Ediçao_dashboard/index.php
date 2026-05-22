<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Editor: Dashboard</title>
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
            <a href="../Gerencia_de_paginas.php/index.php" class="text-sm text-teal-600 font-semibold mb-2 block"><i class="fas fa-arrow-left mr-2"></i> Voltar ao Dashboard</a>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-teal-600 flex items-center justify-center rounded-lg text-white"><i class="fas fa-th-large"></i></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Editor: Dashboard</h2>
                    <p class="text-slate-500">Personalize a aparência e widgets do seu dashboard</p>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-2 space-y-6">
                <!-- Configurações Gerais -->
                <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-800 mb-4"><i class="fas fa-cog mr-2 text-slate-400"></i> Configurações Gerais</h4>
                    <label class="text-xs font-bold text-slate-500 uppercase">Fonte</label>
                    <select class="w-full border rounded-lg p-3 mt-1 mb-4 bg-white"><option>Inter</option></select>
                    <label class="text-xs font-bold text-slate-500 uppercase">Tamanho da Fonte</label>
                    <div class="grid grid-cols-2 gap-4 mt-1 mb-2">
                        <button class="border py-2 rounded-lg text-sm text-slate-600">Pequeno</button>
                        <button class="bg-teal-500 text-white py-2 rounded-lg text-sm">Médio</button>
                        <button class="border py-2 rounded-lg text-sm text-slate-600">Grande</button>
                        <button class="border py-2 rounded-lg text-sm text-slate-600">Extra Grande</button>
                    </div>
                </section>

                <!-- Categorias dos Gráficos -->
                <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-800 mb-4 flex items-center"><i class="fas fa-filter mr-2 text-slate-400"></i> Categorias dos Gráficos</h4>
                    <div class="space-y-4">
                        <div><div class="flex justify-between text-xs font-bold text-slate-500 uppercase mb-2"><span>Receitas (4/4)</span><span class="text-teal-600">Desmarcar Todas</span></div>
                        <div class="space-y-2"><label class="flex items-center gap-2 text-sm"><input type="checkbox" checked> Venda de Produtos</label><label class="flex items-center gap-2 text-sm"><input type="checkbox" checked> Prestação de Serviços</label><label class="flex items-center gap-2 text-sm"><input type="checkbox" checked> Rendimentos</label><label class="flex items-center gap-2 text-sm"><input type="checkbox" checked> Outras Receitas</label></div></div>
                        <hr>
                        <div><div class="flex justify-between text-xs font-bold text-slate-500 uppercase mb-2"><span>Despesas (7/7)</span><span class="text-teal-600">Desmarcar Todas</span></div>
                        <div class="space-y-2"><label class="flex items-center gap-2 text-sm"><input type="checkbox" checked> Folha de Pagamento</label><label class="flex items-center gap-2 text-sm"><input type="checkbox" checked> Despesas Operacionais</label><label class="flex items-center gap-2 text-sm"><input type="checkbox" checked> Fornecedores</label><label class="flex items-center gap-2 text-sm"><input type="checkbox" checked> Marketing e Vendas</label><label class="flex items-center gap-2 text-sm"><input type="checkbox" checked> Impostos e Taxas</label><label class="flex items-center gap-2 text-sm"><input type="checkbox" checked> TI e Equipamentos</label><label class="flex items-center gap-2 text-sm"><input type="checkbox" checked> Outras Despesas</label></div></div>
                    </div>
                </section>

                <!-- Textos -->
                <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-800 mb-6 flex items-center"><i class="fas fa-pen mr-2 text-slate-400"></i> Textos Personalizados</h4>
                    <div class="space-y-4">
                        <div><label class="text-[10px] uppercase font-bold text-slate-500">Título da Página</label><input class="w-full border rounded-lg p-2 text-sm" value="Visão Geral Financeira"></div>
                        <div><label class="text-[10px] uppercase font-bold text-slate-500">Card de Saldo</label><input class="w-full border rounded-lg p-2 text-sm" value="Saldo Total"></div>
                        <div><label class="text-[10px] uppercase font-bold text-slate-500">Card de Receitas</label><input class="w-full border rounded-lg p-2 text-sm" value="Receitas"></div>
                        <div><label class="text-[10px] uppercase font-bold text-slate-500">Card de Despesas</label><input class="w-full border rounded-lg p-2 text-sm" value="Despesas"></div>
                        <div><label class="text-[10px] uppercase font-bold text-slate-500">Título do Gráfico de Receitas</label><input class="w-full border rounded-lg p-2 text-sm" value="Receitas vs Despesas"></div>
                        <div><label class="text-[10px] uppercase font-bold text-slate-500">Título do Gráfico de Despesas</label><input class="w-full border rounded-lg p-2 text-sm" value="Despesas por Categoria"></div>
                    </div>
                </section>
            </div>

            <!-- Widgets -->
            <div class="col-span-1">
                <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-800 mb-4">Widgets Ativos (8/8)</h4>
                    <div class="space-y-3">
                        <div class="border rounded-lg p-3 text-xs">
                            <div class="flex justify-between items-center font-bold mb-2"><span>1 $ Saldo Total</span><div class="flex gap-2"><i class="fas fa-times cursor-pointer"></i><i class="fas fa-eye cursor-pointer"></i></div></div>
                            <div class="flex gap-2"><button class="bg-blue-100 px-3 py-1 rounded">Pequeno</button><button class="bg-blue-100 px-3 py-1 rounded">Médio</button><button class="bg-teal-500 text-white px-3 py-1 rounded">Grande</button></div>
                        </div>
                        <div class="border rounded-lg p-3 flex justify-between items-center text-xs"><span>2 Receitas</span><div class="text-slate-400"><i class="fas fa-pen mr-2"></i><i class="fas fa-eye"></i></div></div>
                        <div class="border rounded-lg p-3 flex justify-between items-center text-xs"><span>3 Despesas</span><div class="text-slate-400"><i class="fas fa-pen mr-2"></i><i class="fas fa-eye"></i></div></div>
                        <div class="border rounded-lg p-3 flex justify-between items-center text-xs"><span>4 Total de Transações</span><div class="text-slate-400"><i class="fas fa-pen mr-2"></i><i class="fas fa-eye"></i></div></div>
                        <div class="border rounded-lg p-3 flex justify-between items-center text-xs"><span>5 Gráfico de Despesas</span><div class="text-slate-400"><i class="fas fa-pen mr-2"></i><i class="fas fa-eye"></i></div></div>
                    </div>
                    <button class="w-full mt-6 bg-slate-900 text-white py-3 rounded-xl font-bold">Salvar Alterações</button>
                    <button class="w-full mt-3 border border-red-200 text-red-500 py-3 rounded-xl font-bold">Restaurar Padrão</button>
                </section>
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