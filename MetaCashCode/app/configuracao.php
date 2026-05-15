<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - MetaCash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { background-color: #f1f5f9; font-family: 'Inter', sans-serif; }
        
        /* Estilos Sidebar */
        .bg-sidebar { background-color: #0d1b2a; }
        .bg-active { background-color: #26a69a; }
        .bg-button-nav { background-color: #1b3a57; }
        .text-sidebar { color: #94a3b8; }

        /* Estilos Conteúdo */
        .card { background: white; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 1.5rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .input-field { width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px; margin-top: 4px; outline: none; background: #fff; }
        .input-field:focus { border-color: #2dd4bf; ring: 2px ring #2dd4bf; }
        .btn-update { background-color: #26a69a; color: white; padding: 8px 16px; border-radius: 8px; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: 0.3s; font-size: 0.875rem; }
        .btn-update:hover { background-color: #1f8a80; }
    </style>
</head>
<body class="flex min-h-screen">

    <!-- SIDEBAR (Conforme image_9cd836.png) -->
    <aside class="flex flex-col w-64 bg-sidebar text-white p-4 sticky top-0 h-screen">
        <div class="flex items-center gap-3 mb-8 px-2">
            <div class="bg-teal-500 p-2 rounded-lg text-white">
                <i class="fa-solid fa-building-columns text-xl"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold leading-none">MetaCash</h1>
                <p class="text-[10px] text-gray-400 uppercase tracking-wider">Gestão Empresarial</p>
            </div>
        </div>

        <hr class="border-gray-700 mb-6">

        <nav class="flex-1 space-y-1">
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-sidebar hover:text-white transition-colors">
                <i class="fa-solid fa-chart-pie w-5"></i> <span class="text-sm font-medium">Dashboard</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-sidebar hover:text-white transition-colors">
                <i class="fa-solid fa-receipt w-5"></i> <span class="text-sm font-medium">Transações</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-sidebar hover:text-white transition-colors">
                <i class="fa-solid fa-users w-5"></i> <span class="text-sm font-medium">Equipe</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-sidebar hover:text-white transition-colors">
                <i class="fa-solid fa-file-lines w-5"></i> <span class="text-sm font-medium">Gerenciar Páginas</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-sidebar hover:text-white transition-colors">
                <i class="fa-solid fa-clock-rotate-left w-5"></i> <span class="text-sm font-medium">Histórico</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-3 bg-active rounded-lg text-white font-semibold mt-2">
                <i class="fa-solid fa-gear w-5"></i> <span class="text-sm">Configurações</span>
            </a>

            <button class="w-full flex items-center justify-center py-3 bg-button-nav rounded-lg text-sm text-white font-medium mt-4">
                Baixar Relatório
            </button>
        </nav>

        <div class="mt-auto">
            <hr class="border-gray-700 mb-6">
            <div class="bg-[#1b3a57] rounded-xl p-3 flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-teal-400 rounded-full flex items-center justify-center text-white font-bold">U</div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold truncate">Usuário</p>
                    <p class="text-xs text-gray-400 truncate">usuario@exemplo.com</p>
                </div>
            </div>
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-sidebar hover:text-red-400 transition-colors">
                <i class="fa-solid fa-right-from-bracket w-5"></i> <span class="text-sm">Sair</span>
            </a>
        </div>
    </aside>

    <!-- CONTEÚDO PRINCIPAL (Conforme image_9cdc1c.png) -->
    <main class="flex-1 p-10 overflow-y-auto">
        <header class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Configurações</h1>
            <p class="text-slate-500 text-sm">Personalize sua experiência no MetaCash</p>
        </header>

        <!-- Sessão: Informações da Empresa -->
        <section class="card">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-blue-100 p-2 rounded-lg text-blue-600"><i class="fa-solid fa-building"></i></div>
                <h2 class="font-bold text-slate-700 text-lg">Informações da Empresa</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase">Nome da Empresa <span class="text-red-500">*</span></label>
                    <input type="text" class="input-field" value="Minha Empresa">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase">CNPJ <span class="text-red-500">*</span></label>
                    <input type="text" class="input-field" placeholder="00.000.000/0000-00">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase">Data de Início da Contabilidade <span class="text-red-500">*</span></label>
                    <input type="date" class="input-field">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-600 uppercase">Ano Fiscal <span class="text-red-500">*</span></label>
                    <input type="number" class="input-field" value="2026">
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button class="btn-update"><i class="fa-solid fa-rotate"></i> Atualizar</button>
            </div>
        </section>

        <!-- Sessão: Sua Logo -->
        <section class="card">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-blue-100 p-2 rounded-lg text-blue-600"><i class="fa-solid fa-upload"></i></div>
                <h2 class="font-bold text-slate-700 text-lg">Sua Logo</h2>
            </div>
            <div class="border-dashed border-2 border-slate-200 rounded-lg p-6 flex flex-col items-start">
                <button class="bg-sky-100 text-sky-600 px-4 py-2 rounded-md font-semibold text-sm mb-2">Escolher Arquivo</button>
                <p class="text-[11px] text-slate-400 uppercase">Formatos aceitos: PNG, JPG, SVG. Tamanho máximo: 2MB</p>
            </div>
            <div class="flex justify-end mt-4">
                <button class="btn-update"><i class="fa-solid fa-rotate"></i> Atualizar</button>
            </div>
        </section>

        <!-- NOVA Sessão: Saldo Inicial -->
        <section class="card">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-blue-100 p-2 rounded-lg text-blue-600"><i class="fa-solid fa-dollar-sign"></i></div>
                <h2 class="font-bold text-slate-700 text-lg">Saldo Inicial</h2>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 uppercase">Saldo Total (R$) <span class="text-red-500">*</span></label>
                <input type="text" class="input-field" value="0,00">
                <p class="text-[11px] text-slate-400 mt-2 italic leading-tight">
                    Este é o saldo que sua empresa tinha antes de começar a usar o MetaCash. <br>
                    O sistema calcula automaticamente: Saldo Inicial + Receitas - Despesas.
                </p>
            </div>
            <div class="flex justify-end mt-4">
                <button class="btn-update"><i class="fa-solid fa-rotate"></i> Atualizar</button>
            </div>
        </section>

        <!-- Sessão: Categorias Personalizadas -->
        <section class="card">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-blue-100 p-2 rounded-lg text-blue-600"><i class="fa-solid fa-tags"></i></div>
                <h2 class="font-bold text-slate-700 text-lg">Categorias Personalizadas</h2>
            </div>
            
            <div class="bg-slate-50 p-4 rounded-lg flex flex-wrap md:flex-nowrap gap-2 mb-6 border border-slate-100">
                <input type="text" class="flex-1 border border-slate-300 rounded-md px-3 text-sm" placeholder="Nome da categoria...">
                <select class="border border-slate-300 rounded-md px-3 bg-white text-sm">
                    <option>Receita</option>
                    <option>Despesa</option>
                </select>
                <button class="bg-teal-400 text-white px-4 py-2 rounded-md font-bold text-sm hover:bg-teal-500 transition-colors">+ Adicionar</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Coluna Receitas -->
                <div>
                    <h3 class="text-xs font-bold text-teal-600 mb-3 uppercase tracking-wider">Receitas</h3>
                    <?php 
                    $receitas = ["Venda de Produtos", "Prestação de Serviços", "Rendimentos", "Outras Receitas"];
                    foreach($receitas as $r): ?>
                        <div class="flex justify-between items-center border border-slate-200 rounded-md px-3 py-2 mb-2 text-sm text-slate-600 bg-white">
                            <span>• <?php echo $r; ?></span>
                            <i class="fa-solid fa-xmark text-red-400 cursor-pointer hover:text-red-600"></i>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Coluna Despesas -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 mb-3 uppercase tracking-wider">Despesas</h3>
                    <?php 
                    $despesas = ["Folha de Pagamento (Pessoal)", "Despesas Operacionais (Fixas)", "Fornecedores (Insumos)", "Marketing e Vendas", "Impostos e Taxas", "TI e Equipamentos", "Outras Despesas"];
                    foreach($despesas as $d): ?>
                        <div class="flex justify-between items-center border border-slate-200 rounded-md px-3 py-2 mb-2 text-sm text-slate-600 bg-white">
                            <span>• <?php echo $d; ?></span>
                            <i class="fa-solid fa-xmark text-red-400 cursor-pointer hover:text-red-600"></i>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Botão Salvar Geral -->
        <div class="flex justify-end pb-10">
            <button class="bg-[#0d1b2a] text-white px-8 py-3 rounded-lg font-bold flex items-center gap-2 hover:bg-slate-800 shadow-lg transition-all">
                <i class="fa-solid fa-floppy-disk"></i> Salvar Alterações
            </button>
        </div>
    </main>

</body>
</html>