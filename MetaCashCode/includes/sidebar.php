<?php

$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="w-64 bg-[#0f172a] text-white p-4 flex flex-col fixed h-screen shrink-0 z-40">
    <div class="flex items-center gap-3 mb-10 px-2 pt-2">
        <img src="../assets/img/logoCyano.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover">
        <div class="flex flex-col">
            <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
            <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Gestão Empresarial</span>
        </div>
    </div>

    <nav class="flex-1 space-y-2">
        <a href="../app/dashboardUsuario.php" 
           id="btn-dashboard"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition nav-btn" 
           data-page="dashboardUsuario.php">
            <i class="fas fa-th-large"></i><span class="font-medium">Dashboard</span>
        </a>
        <a href="../app/transacoes.php" 
           id="btn-transacoes"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition nav-btn" 
           data-page="transacoes.php">
            <i class="fas fa-exchange-alt"></i><span class="font-medium">Transações</span>
        </a>
        <button onclick="toggleModal('modalRelatorio')" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition border border-transparent hover:border-slate-700 text-left">
            <i class="fas fa-file-pdf"></i><span class="font-medium">Baixar Relatório</span>
        </button>
    </nav>

    <div class="border-t border-slate-700 pt-4">
        <form method="POST" action="../auth/logout.php">
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition text-left">
                <i class="fas fa-sign-out-alt"></i><span class="font-medium">Sair</span>
            </button>
        </form>
    </div>
</aside>

<script>
// Função para destacar o botão ativo
document.addEventListener('DOMContentLoaded', function() {
    const currentPage = '<?php echo $current_page; ?>';
    const navButtons = document.querySelectorAll('.nav-btn');
    
    navButtons.forEach(btn => {
        const btnPage = btn.getAttribute('data-page');
        if (btnPage === currentPage) {
            // Remove classes de outros botões
            navButtons.forEach(b => {
                b.classList.remove('bg-[#2dd4bf]', 'text-white', 'shadow-lg');
                b.classList.add('text-gray-400', 'hover:bg-slate-800');
            });
            // Adiciona classes ao botão ativo
            btn.classList.remove('text-gray-400', 'hover:bg-slate-800');
            btn.classList.add('bg-[#2dd4bf]', 'text-white', 'shadow-lg');
        }
    });
});
</script>
