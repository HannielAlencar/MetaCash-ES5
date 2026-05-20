<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Gerenciamento de Páginas</title>
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
        <header class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900">Gerenciamento de Páginas</h2>
            <p class="text-slate-500">Configure a visibilidade e ordem das páginas do sistema</p>
        </header>

        <!-- Seção Como Funciona -->
        <section class="bg-white border border-gray-200 rounded-2xl p-6 mb-6 shadow-sm">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-teal-600 mt-1"></i>
                <div>
                    <h4 class="font-bold text-slate-800 mb-2">Como Funciona</h4>
                    <p class="text-sm text-slate-600 mb-1">• Use os botões de <strong>ativar/desativar</strong> para controlar quais páginas aparecem no menu</p>
                    <p class="text-sm text-slate-600">• Clique em <strong>"Editar"</strong> para ir diretamente à página e fazer alterações</p>
                </div>
            </div>
        </section>

        <!-- Páginas Editáveis -->
        <section class="bg-white border border-gray-200 rounded-2xl p-6 mb-6 shadow-sm">
            <h3 class="font-bold text-lg mb-6">Páginas Editáveis</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-teal-100 flex items-center justify-center rounded-lg text-teal-600"><i class="fas fa-home"></i></div>
                        <div><p class="font-bold text-slate-800">Home</p><p class="text-xs text-slate-400">Página inicial da empresa com logo e apresentação</p></div>
                    </div>
                    <a href="../Ediçao_home.php/index.php" class="inline-flex items-center justify-center px-4 py-2 bg-teal-500 text-white rounded-lg text-sm font-semibold hover:bg-teal-600"><i class="fas fa-pen mr-2"></i> Editar</a>
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-teal-100 flex items-center justify-center rounded-lg text-teal-600"><i class="fas fa-th-large"></i></div>
                        <div><p class="font-bold text-slate-800">Dashboard</p><p class="text-xs text-slate-400">Visão geral dos dados financeiros e métricas principais</p></div>
                    </div>
                    <a href="../Ediçao_dashboard/index.php" class="inline-flex items-center justify-center px-4 py-2 bg-teal-500 text-white rounded-lg text-sm font-semibold hover:bg-teal-600"><i class="fas fa-pen mr-2"></i> Editar</a>
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-teal-100 flex items-center justify-center rounded-lg text-teal-600"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div><p class="font-bold text-slate-800">Transações</p><p class="text-xs text-slate-400">Registro e gerenciamento de receitas e despesas</p></div>
                    </div>
                    <a href="../Ediçao_transaçoes.php/index.php" class="inline-flex items-center justify-center px-4 py-2 bg-teal-500 text-white rounded-lg text-sm font-semibold hover:bg-teal-600"><i class="fas fa-pen mr-2"></i> Editar</a>
                </div>
            </div>
        </section>

        <!-- Paleta de Cores -->
        <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h3 class="font-bold text-lg mb-6"><i class="fas fa-palette mr-2 text-teal-600"></i> Paleta de Cores</h3>
            <div class="grid grid-cols-4 gap-4 mb-8">
                <div class="border rounded-xl p-4 text-center"><div class="flex justify-center gap-1 mb-2"><div class="w-3 h-3 rounded-full bg-slate-900"></div><div class="w-3 h-3 rounded-full bg-teal-500"></div></div><p class="text-xs font-bold">MetaCash Original</p></div>
                <div class="border rounded-xl p-4 text-center"><div class="flex justify-center gap-1 mb-2"><div class="w-3 h-3 rounded-full bg-slate-900"></div><div class="w-3 h-3 rounded-full bg-cyan-600"></div></div><p class="text-xs font-bold">Oceano Profundo</p></div>
                <div class="border rounded-xl p-4 text-center"><div class="flex justify-center gap-1 mb-2"><div class="w-3 h-3 rounded-full bg-emerald-900"></div><div class="w-3 h-3 rounded-full bg-emerald-500"></div></div><p class="text-xs font-bold">Floresta Moderna</p></div>
                <div class="border rounded-xl p-4 text-center"><div class="flex justify-center gap-1 mb-2"><div class="w-3 h-3 rounded-full bg-red-900"></div><div class="w-3 h-3 rounded-full bg-amber-500"></div></div><p class="text-xs font-bold">Sunset Corporativo</p></div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-4">
                <div><label class="text-xs font-bold text-slate-500 uppercase">Cor do menu</label><input type="text" value="#0F2440" class="w-full border rounded-lg p-3 mt-1"></div>
                <div><label class="text-xs font-bold text-slate-500 uppercase">Cor de botão 1</label><input type="text" value="#204C73" class="w-full border rounded-lg p-3 mt-1"></div>
                <div><label class="text-xs font-bold text-slate-500 uppercase">Cor de Destaque</label><input type="text" value="#24A6B6" class="w-full border rounded-lg p-3 mt-1"></div>
                <div><label class="text-xs font-bold text-slate-500 uppercase">Cor de botão 2</label><input type="text" value="#35C59A" class="w-full border rounded-lg p-3 mt-1"></div>
                <div><label class="text-xs font-bold text-slate-500 uppercase">Cor Clara</label><input type="text" value="#5DA4C0" class="w-full border rounded-lg p-3 mt-1"></div>
                <div><label class="text-xs font-bold text-slate-500 uppercase">Cor de Fundo</label><input type="text" value="#FDFEFB" class="w-full border rounded-lg p-3 mt-1"></div>
            </div>
            <div class="bg-slate-50 border p-3 rounded-lg text-xs text-slate-500 italic">Dica: Clique no círculo colorico para abrir o seletor visual ou digite o código hexademical diretamente (ex: #0F2440).</div>
            <div class="text-right mt-6">
                <button class="bg-teal-500 text-white px-8 py-3 rounded-lg font-bold hover:bg-teal-600"><i class="fas fa-sync mr-2"></i> Atualizar</button>
            </div>
        </section>
    </main>
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