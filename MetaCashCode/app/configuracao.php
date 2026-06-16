<!DOCTYPE html>
<html lang="pt-br" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - MetaCash</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght=300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Ajuste do select para manter a seta customizada limpa */
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
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
            console.error("Erro ao ler localStorage, mantendo padrão original:", erro);
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex min-h-screen bg-meta-fundo transition-colors duration-200">

    <aside class="w-64 bg-meta-menu text-white p-4 flex flex-col fixed h-screen shrink-0 z-40 transition-colors duration-200">
        <div class="flex items-center gap-3 mb-10 px-2 pt-2">
            <img src="../assets/img/logo_empresas.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover" onerror="this.onerror=null; this.src='../DashboardGerente/image_75793b.png'; this.onerror=function(){this.src='https://ui-avatars.com/api/?name=MetaCash&background=24A6B6&color=0F2440';}">
            <div class="flex flex-col">
                <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
                <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Gestão Empresarial</span>
            </div>
        </div>

        <nav class="flex-1 space-y-2">
            <a href="../app/dashboardGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-th-large w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="../app/transacoesGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-exchange-alt w-5"></i>
                <span class="font-medium">Transações</span>
            </a>
            <a href="../app/gerenciaEquipe.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-users w-5"></i>
                <span class="font-medium">Equipe</span>
            </a>
            <a href="../app/gerenciaPaginas.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-file-alt w-5"></i>
                <span class="font-medium">Gerenciar Páginas</span>
            </a>
            <a href="../app/historico.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-history w-5"></i>
                <span class="font-medium">Histórico</span>
            </a>
            
            <a href="../app/configuracao.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-meta-destaque text-white shadow-lg transition-colors duration-200">
                <i class="fas fa-cog w-5"></i>
                <span class="font-medium">Configurações</span>
            </a>

            <button onclick="toggleModal('modalRelatorio')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-white/10 hover:text-white transition border border-transparent hover:border-white/5 text-left">
                <i class="fas fa-file-pdf w-5"></i>
                <span class="font-medium">Baixar Relatório</span>
            </button>
        </nav>

        <div class="mt-auto pt-6 border-t border-white/10 space-y-4 pb-2">
            <a href="../app/PerfilGerente.php" class="bg-white/5 p-3 rounded-2xl flex items-center gap-3 border border-white/10 hover:bg-white/10 transition block group">
                <div class="w-10 h-10 bg-meta-destaque rounded-full flex items-center justify-center text-white font-bold text-lg shrink-0 group-hover:scale-105 transition-transform">U</div>
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

    <main class="flex-1 p-10 ml-64 overflow-y-auto">
        <header class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Configurações</h1>
            <p class="text-slate-500 mt-1 text-sm">Personalize os dados operacionais da empresa no MetaCash</p>
        </header>

        <section class="bg-white border border-gray-200 p-8 rounded-3xl shadow-sm mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-meta-clara/10 flex items-center justify-center rounded-lg text-meta-clara"><i class="fa-solid fa-building"></i></div>
                <h2 class="font-bold text-slate-800 text-lg">Informações da Empresa</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nome da Empresa <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 bg-slate-50 outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 mt-2" value="Minha Empresa">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">CNPJ <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 bg-slate-50 outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 mt-2" placeholder="00.000.000/0000-00">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Início da Contabilidade <span class="text-red-500">*</span></label>
                    <input type="date" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 bg-slate-50 outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 mt-2">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ano Fiscal <span class="text-red-500">*</span></label>
                    <input type="number" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 bg-slate-50 outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 mt-2" value="2026">
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button class="bg-meta-btn1 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 hover:opacity-90 active:scale-95 transition-all shadow-md">
                    <i class="fa-solid fa-rotate text-xs"></i> Atualizar Empresa
                </button>
            </div>
        </section>

        <section class="bg-white border border-gray-200 p-8 rounded-3xl shadow-sm mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-meta-clara/10 flex items-center justify-center rounded-lg text-meta-clara"><i class="fa-solid fa-upload"></i></div>
                <h2 class="font-bold text-slate-800 text-lg">Sua Logo</h2>
            </div>
            <div class="border-dashed border-2 border-slate-200 rounded-2xl p-8 flex flex-col items-start bg-slate-50/50 hover:bg-slate-50 transition-colors">
                <button class="bg-meta-btn1 text-white px-5 py-2.5 rounded-xl font-bold text-sm mb-3 hover:opacity-90 transition-all shadow-sm">Escolher Arquivo</button>
                <p class="text-[11px] text-slate-400 uppercase font-semibold tracking-wider">Formatos aceitos: PNG, JPG, SVG. Tamanho máximo: 2MB</p>
            </div>
        </section>

        <section class="bg-white border border-gray-200 p-8 rounded-3xl shadow-sm mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-meta-clara/10 flex items-center justify-center rounded-lg text-meta-clara"><i class="fa-solid fa-tags"></i></div>
                <h2 class="font-bold text-slate-800 text-lg">Categorias Personalizadas</h2>
            </div>
            
            <div class="bg-slate-50 p-5 rounded-2xl flex flex-wrap md:flex-nowrap gap-3 mb-6 border border-slate-100 shadow-inner">
                <input type="text" class="flex-1 border border-slate-300 rounded-xl px-4 py-3 text-sm outline-none focus:bg-white focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200" placeholder="Nome da categoria...">
                <select class="border border-slate-300 rounded-xl px-4 py-3 bg-white text-sm font-semibold text-slate-600 outline-none focus:border-meta-destaque focus:ring-4 focus:ring-meta-destaque/20 transition-all duration-200 min-w-[120px]">
                    <option>Receita</option>
                    <option>Despesa</option>
                </select>
                <button class="bg-meta-destaque text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-md active:scale-95 hover:opacity-90">+ Adicionar</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xs font-bold text-meta-destaque mb-4 uppercase tracking-wider">Receitas</h3>
                    <?php 
                    $receitas = ["Venda de Produtos", "Prestação de Serviços", "Rendimentos", "Outras Receitas"];
                    foreach($receitas as $r): ?>
                        <div class="flex justify-between items-center border border-slate-200 rounded-xl px-4 py-3 mb-2.5 text-sm text-slate-600 bg-white hover:border-slate-300 transition-colors shadow-sm">
                            <span class="font-medium">• <?php echo $r; ?></span>
                            <i class="fa-solid fa-xmark text-slate-400 cursor-pointer hover:text-red-500 transition-colors"></i>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-400 mb-4 uppercase tracking-wider">Despesas</h3>
                    <?php 
                    $despesas = ["Folha de Pagamento", "Despesas Operacionais", "Fornecedores", "Marketing", "Impostos e Taxas", "TI e Equipamentos"];
                    foreach($despesas as $d): ?>
                        <div class="flex justify-between items-center border border-slate-200 rounded-xl px-4 py-3 mb-2.5 text-sm text-slate-600 bg-white hover:border-slate-300 transition-colors shadow-sm">
                            <span class="font-medium">• <?php echo $d; ?></span>
                            <i class="fa-solid fa-xmark text-slate-400 cursor-pointer hover:text-red-500 transition-colors"></i>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <div class="flex justify-end pb-10">
            <button class="bg-meta-destaque text-white px-8 py-4 rounded-2xl font-bold flex items-center gap-2 hover:opacity-90 shadow-xl transition-all hover:-translate-y-0.5 active:translate-y-0">
                <i class="fa-solid fa-floppy-disk"></i> Salvar Todas as Alterações
            </button>
        </div>
    </main>

    <div id="modalRelatorio" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-[60] p-4 backdrop-blur-sm">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl p-8">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-extrabold text-slate-800">Baixar Relatório</h3>
                <button onclick="toggleModal('modalRelatorio')" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="gerar_pdf.php" method="GET" target="_blank" class="space-y-6">
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Tipo de Transação</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="e" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Receita</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="s" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Despesa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="todos" checked class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Ambos</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Período</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="mensal" checked class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Mensal</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="anual" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Anual</div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Mês</label>
                        <select name="mes" class="w-full p-4 rounded-2xl border border-slate-200 bg-white text-slate-700 font-medium appearance-none focus:outline-none focus:border-meta-destaque focus:ring-2 focus:ring-meta-destaque/20 transition-all cursor-pointer">
                            <option value="5" selected>Maio</option>
                            </select>
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Ano</label>
                        <select name="ano" class="w-full p-4 rounded-2xl border border-slate-200 bg-white text-slate-700 font-medium appearance-none focus:outline-none focus:border-meta-destaque focus:ring-2 focus:ring-meta-destaque/20 transition-all cursor-pointer">
                            <option value="2026" selected>2026</option>
                            <option value="2025">2025</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('modalRelatorio')" class="flex-1 py-4 border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all">Cancelar</button>
                    <button type="submit" class="flex-1 py-4 bg-meta-destaque text-white font-bold rounded-2xl shadow-lg hover:opacity-90 transition-all flex items-center justify-center gap-2">
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
        window.onclick = function(event) {
            const mRel = document.getElementById('modalRelatorio');
            if (event.target == mRel) toggleModal('modalRelatorio');
        }
    </script>
</body>
</html>