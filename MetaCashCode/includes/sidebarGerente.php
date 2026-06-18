<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Identifica automaticamente o nome do arquivo atual em letras minúsculas
$pagina_atual = strtolower(basename($_SERVER['PHP_SELF']));

// Resgata os dados reais do usuário logado na sessão para manter o rodapé dinâmico
$nome_usuario = $_SESSION['nome_completo'] ?? 'Gerente';
$email_usuario = $_SESSION['email'] ?? 'gerente@empresa.com';
$inicial_nome = strtoupper(substr(trim($nome_usuario), 0, 1));
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght=300;400;500;600;700;800&display=swap');
    :root {
        --meta-menu: #0F2440;
        --meta-btn1: #204C73;
        --meta-destaque: #24A6B6;
        --meta-btn2: #35C59A;
        --meta-clara: #5DA4C0;
        --meta-fundo: #FDFEFB;
    }
    body { font-family: 'Inter', sans-serif; }
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
    } catch (e) { console.error(e); }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<aside class="w-64 bg-[#0f172a] text-white p-4 flex flex-col fixed h-screen shrink-0 z-40 text-sm">
    <div class="flex items-center gap-3 mb-10 px-2 pt-2">
        <img src="../assets/img/logo_empresas.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover" onerror="this.onerror=null; this.src='../DashboardGerente/image_75793b.png'; this.onerror=function(){this.src='https://ui-avatars.com/api/?name=MetaCash&background=2dd4bf&color=0f172a';}">
        <div class="flex flex-col">
            <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
            <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Gestão Empresarial</span>
        </div>
    </div>

    <nav class="flex-1 space-y-2">
        <a href="../app/dashboardGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($pagina_atual === 'dashboardgerente.php') ? 'bg-[#2dd4bf] text-white shadow-lg font-semibold' : 'text-gray-400 hover:bg-slate-800 hover:text-white' ?>">
            <i class="fas fa-th-large w-5"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="../app/TransacoesGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($pagina_atual === 'transacoesgerente.php') ? 'bg-[#2dd4bf] text-white shadow-lg font-semibold' : 'text-gray-400 hover:bg-slate-800 hover:text-white' ?>">
            <i class="fas fa-exchange-alt w-5"></i>
            <span class="font-medium">Transações</span>
        </a>

        <a href="../app/gerenciaEquipe.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($pagina_atual === 'gerenciaequipe.php') ? 'bg-[#2dd4bf] text-white shadow-lg font-semibold' : 'text-gray-400 hover:bg-slate-800 hover:text-white' ?>">
            <i class="fas fa-users w-5"></i>
            <span class="font-medium">Equipe</span>
        </a>

        <a href="../app/gerenciaPaginas.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($pagina_atual === 'gerenciapaginas.php') ? 'bg-[#2dd4bf] text-white shadow-lg font-semibold' : 'text-gray-400 hover:bg-slate-800 hover:text-white' ?>">
            <i class="fas fa-file-alt w-5"></i>
            <span class="font-medium">Gerenciar Páginas</span>
        </a>

        <a href="../app/historico.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($pagina_atual === 'historico.php') ? 'bg-[#2dd4bf] text-white shadow-lg font-semibold' : 'text-gray-400 hover:bg-slate-800 hover:text-white' ?>">
            <i class="fas fa-history w-5"></i>
            <span class="font-medium">Histórico</span>
        </a>

        <a href="../app/configuracao.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($pagina_atual === 'configuracao.php') ? 'bg-[#2dd4bf] text-white shadow-lg font-semibold' : 'text-gray-400 hover:bg-slate-800 hover:text-white' ?>">
            <i class="fas fa-cog w-5"></i>
            <span class="font-medium">Configurações</span>
        </a>

        <button onclick="toggleModal('modalRelatorio')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition border border-transparent hover:border-slate-700 text-left">
            <i class="fas fa-file-pdf w-5"></i>
            <span class="font-medium">Baixar Relatório</span>
        </button>
    </nav>

    <div class="mt-auto pt-6 border-t border-slate-800 space-y-4 pb-2">
        <a href="../app/PerfilGerente.php" class="bg-[#1e3a5f]/40 p-3 rounded-2xl flex items-center gap-3 border border-slate-700/50 hover:bg-[#1e3a5f]/60 transition block group <?= ($pagina_atual === 'perfilgerente.php') ? 'border-[#2dd4bf]' : '' ?>">
            <div class="w-10 h-10 bg-[#2dd4bf] rounded-full flex items-center justify-center text-[#0f172a] font-bold text-lg shrink-0 group-hover:scale-105 transition-transform">
                <?= $inicial_nome; ?>
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-sm font-bold truncate"><?= htmlspecialchars($nome_usuario); ?></span>
                <span class="text-[10px] text-gray-400 truncate"><?= htmlspecialchars($email_usuario); ?></span>
            </div>
        </a>
        <a href="../auth/logout.php" class="flex items-center gap-3 px-4 py-2 text-gray-400 hover:text-white transition group">
            <i class="fas fa-sign-out-alt rotate-180 group-hover:text-red-400 transition-colors"></i>
            <span class="font-medium">Sair</span>
        </a>
    </div>
</aside>