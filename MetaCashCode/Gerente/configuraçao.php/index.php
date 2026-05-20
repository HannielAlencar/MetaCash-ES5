<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - MetaCash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { background-color: #f1f5f9; font-family: 'Inter', sans-serif; }
        .modal-backdrop { backdrop-filter: blur(6px); }
        
        /* Definição de styles para os componentes de Configuração */
        .card {
            background-color: white;
            border-radius: 1.5rem; /* 24px */
            border: 1px solid #e2e8f0; /* slate-200 */
            padding: 2rem; /* 32px */
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
        }
        .input-field {
            width: 100%;
            border-radius: 0.75rem; /* 12px */
            border: 1px solid #cbd5e1; /* slate-300 */
            padding: 0.75rem 1rem;
            margin-top: 0.5rem;
            outline: none;
            font-size: 0.875rem;
            color: #334155;
            transition: all 0.2s;
            background-color: #f8fafc;
        }
        .input-field:focus {
            border-color: #2dd4bf;
            background-color: white;
            box-shadow: 0 0 0 4px rgba(45, 212, 191, 0.1);
        }
        .btn-update {
            background-color: #0f172a;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 0.875rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-update:hover {
            background-color: #1e293b;
            transform: translateY(-1px);
        }
        .btn-update:active {
            transform: translateY(0);
        }
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
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
                <a href="../Gerencia_de_paginas.php/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="font-medium">Gerenciar Páginas</span>
                </a>
                <!-- Histórico -->
                <a href="../Historico.php/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <i class="fas fa-history w-5"></i>
                    <span class="font-medium">Histórico</span>
                </a>
                <!-- Configurações (Ativo) -->
                <a href="../configuraçao.php/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#2dd4bf] text-white shadow-lg transition">
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

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="flex-1 p-10 ml-64 overflow-y-auto">
            <header class="mb-8">
                <h1 class="text-3xl font-extrabold text-[#0f172a] tracking-tight">Configurações</h1>
                <p class="text-slate-500 mt-1 text-sm">Personalize sua experiência no MetaCash</p>
            </header>

            <!-- Seção: Informações da Empresa -->
            <section class="card">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600"><i class="fa-solid fa-building text-sm"></i></div>
                    <h2 class="font-bold text-slate-800 text-lg">Informações da Empresa</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nome da Empresa <span class="text-red-500">*</span></label>
                        <input type="text" class="input-field" value="Minha Empresa">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">CNPJ <span class="text-red-500">*</span></label>
                        <input type="text" class="input-field" placeholder="00.000.000/0000-00">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Data de Início da Contabilidade <span class="text-red-500">*</span></label>
                        <input type="date" class="input-field">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ano Fiscal <span class="text-red-500">*</span></label>
                        <input type="number" class="input-field" value="2026">
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button class="btn-update"><i class="fa-solid fa-rotate text-xs"></i> Atualizar</button>
                </div>
            </section>

            <!-- Seção: Sua Logo -->
            <section class="card">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600"><i class="fa-solid fa-upload text-sm"></i></div>
                    <h2 class="font-bold text-slate-800 text-lg">Sua Logo</h2>
                </div>
                <div class="border-dashed border-2 border-slate-200 rounded-2xl p-8 flex flex-col items-start bg-slate-50/50 hover:bg-slate-50 transition-colors">
                    <button class="bg-[#0f172a] text-white px-5 py-2.5 rounded-xl font-bold text-sm mb-3 hover:bg-slate-800 transition-all shadow-sm">Escolher Arquivo</button>
                    <p class="text-[11px] text-slate-400 uppercase font-semibold tracking-wider">Formatos aceitos: PNG, JPG, SVG. Tamanho máximo: 2MB</p>
                </div>
                <div class="flex justify-end mt-6">
                    <button class="btn-update"><i class="fa-solid fa-rotate text-xs"></i> Atualizar</button>
                </div>
            </section>

            <!-- Seção: Saldo Inicial -->
            <section class="card">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600"><i class="fa-solid fa-dollar-sign text-sm"></i></div>
                    <h2 class="font-bold text-slate-800 text-lg">Saldo Inicial</h2>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Saldo Total (R$) <span class="text-red-500">*</span></label>
                    <input type="text" class="input-field" value="0,00">
                    <p class="text-xs text-slate-400 mt-3 italic leading-relaxed">
                        Este é o saldo que sua empresa tinha antes de começar a usar o MetaCash. <br>
                        O sistema calcula automaticamente: Saldo Inicial + Receitas - Despesas.
                    </p>
                </div>
                <div class="flex justify-end mt-6">
                    <button class="btn-update"><i class="fa-solid fa-rotate text-xs"></i> Atualizar</button>
                </div>
            </section>

            <!-- Seção: Categorias Personalizadas -->
            <section class="card">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600"><i class="fa-solid fa-tags text-sm"></i></div>
                    <h2 class="font-bold text-slate-800 text-lg">Categorias Personalizadas</h2>
                </div>
                
                <div class="bg-slate-50 p-5 rounded-2xl flex flex-wrap md:flex-nowrap gap-3 mb-6 border border-slate-100 shadow-inner">
                    <input type="text" class="flex-1 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20" placeholder="Nome da categoria...">
                    <select class="border border-slate-200 rounded-xl px-4 py-3 bg-white text-sm font-semibold text-slate-600 focus:outline-none focus:ring-2 focus:ring-teal-500/20 min-w-[120px]">
                        <option>Receita</option>
                        <option>Despesa</option>
                    </select>
                    <button class="bg-[#2dd4bf] hover:bg-teal-500 text-[#0f172a] px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-md active:scale-95">+ Adicionar</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Coluna Receitas -->
                    <div>
                        <h3 class="text-xs font-bold text-teal-600 mb-4 uppercase tracking-wider">Receitas</h3>
                        <?php 
                        $receitas = ["Venda de Produtos", "Prestação de Serviços", "Rendimentos", "Outras Receitas"];
                        foreach($receitas as $r): ?>
                            <div class="flex justify-between items-center border border-slate-200 rounded-xl px-4 py-3 mb-2.5 text-sm text-slate-600 bg-white hover:border-slate-300 transition-colors shadow-sm">
                                <span class="font-medium">• <?php echo $r; ?></span>
                                <i class="fa-solid fa-xmark text-slate-400 cursor-pointer hover:text-red-500 transition-colors"></i>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Coluna Despesas -->
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 mb-4 uppercase tracking-wider">Despesas</h3>
                        <?php 
                        $despesas = ["Folha de Pagamento (Pessoal)", "Despesas Operacionais (Fixas)", "Fornecedores (Insumos)", "Marketing e Vendas", "Impostos e Taxas", "TI e Equipamentos", "Outras Despesas"];
                        foreach($despesas as $d): ?>
                            <div class="flex justify-between items-center border border-slate-200 rounded-xl px-4 py-3 mb-2.5 text-sm text-slate-600 bg-white hover:border-slate-300 transition-colors shadow-sm">
                                <span class="font-medium">• <?php echo $d; ?></span>
                                <i class="fa-solid fa-xmark text-slate-400 cursor-pointer hover:text-red-500 transition-colors"></i>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- Botão Salvar Geral -->
            <div class="flex justify-end pb-10">
                <button class="bg-[#0f172a] text-white px-8 py-4 rounded-2xl font-bold flex items-center gap-2 hover:bg-slate-800 shadow-xl transition-all hover:-translate-y-0.5 active:translate-y-0">
                    <i class="fa-solid fa-floppy-disk"></i> Salvar Alterações
                </button>
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
    </script>
</body>
</html>