<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pagina_atual = strtolower(basename($_SERVER['PHP_SELF']));
$nome_usuario = $_SESSION['nome_completo'] ?? 'Gerente';
$email_usuario = $_SESSION['email'] ?? 'gerente@empresa.com';
$inicial_nome = strtoupper(substr(trim($nome_usuario), 0, 1));
?>

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
                        text: 'var(--meta-text)',
                        active: 'var(--meta-active)'
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
        /* Variáveis de texto adicionadas */
        --meta-text: #94a3b8;   /* Cor do texto inativo */
        --meta-active: #ffffff; /* Cor do texto ativo */
    }
</style>

<aside class="w-64 bg-meta-menu text-meta-active p-4 flex flex-col fixed h-screen shrink-0 z-40 text-sm">
    <div class="flex items-center gap-3 mb-10 px-2 pt-2">
        <img src="../assets/img/logo_empresas.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover" onerror="this.onerror=null; this.src='../DashboardGerente/image_75793b.png';">
        <div class="flex flex-col">
            <span class="font-bold text-xl leading-tight text-meta-active">MetaCash</span>
            <span class="text-[10px] text-meta-text uppercase tracking-wider font-semibold">Gestão Empresarial</span>
        </div>
    </div>

    <nav class="flex-1 space-y-2">
        <?php 
        $links = [
            ['url' => '../app/dashboardGerente.php', 'icon' => 'fa-th-large', 'label' => 'Dashboard', 'file' => 'dashboardgerente.php'],
            ['url' => '../app/TransacoesGerente.php', 'icon' => 'fa-exchange-alt', 'label' => 'Transações', 'file' => 'transacoesgerente.php'],
            ['url' => '../app/gerenciaEquipe.php', 'icon' => 'fa-users', 'label' => 'Equipe', 'file' => 'gerenciaequipe.php'],
            ['url' => '../app/gerenciaPaginas.php', 'icon' => 'fa-file-alt', 'label' => 'Gerenciar Páginas', 'file' => 'gerenciapaginas.php'],
            ['url' => '../app/historico.php', 'icon' => 'fa-history', 'label' => 'Histórico', 'file' => 'historico.php'],
            ['url' => '../app/configuracao.php', 'icon' => 'fa-cog', 'label' => 'Configurações', 'file' => 'configuracao.php'],
        ];

        foreach ($links as $link) {
            $ativo = ($pagina_atual === $link['file']);
            $classes = $ativo 
                ? 'bg-meta-destaque text-meta-active shadow-lg font-semibold' 
                : 'text-meta-text hover:bg-white/10 hover:text-meta-active';
            echo '<a href="'.$link['url'].'" class="flex items-center gap-3 px-4 py-3 rounded-xl transition '.$classes.'">
                    <i class="fas '.$link['icon'].' w-5"></i>
                    <span class="font-medium">'.$link['label'].'</span>
                  </a>';
        }
        ?>

        <button onclick="toggleModal('modalRelatorio')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-meta-text hover:bg-white/10 hover:text-meta-active transition border border-transparent hover:border-slate-700 text-left">
            <i class="fas fa-file-pdf w-5"></i>
            <span class="font-medium">Baixar Relatório</span>
        </button>
    </nav>

    <div class="mt-auto pt-6 border-t border-white/10 space-y-4 pb-2">
        <a href="../app/PerfilGerente.php" class="bg-meta-btn1/40 p-3 rounded-2xl flex items-center gap-3 border border-white/10 hover:bg-meta-btn1/60 transition block group <?= ($pagina_atual === 'perfilgerente.php') ? 'border-meta-destaque' : '' ?>">
            <div class="w-10 h-10 bg-meta-destaque rounded-full flex items-center justify-center text-meta-menu font-bold text-lg shrink-0 group-hover:scale-105 transition-transform">
                <?= $inicial_nome; ?>
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-sm font-bold truncate text-meta-active"><?= htmlspecialchars($nome_usuario); ?></span>
                <span class="text-[10px] text-meta-text truncate"><?= htmlspecialchars($email_usuario); ?></span>
            </div>
        </a>
        <a href="../auth/logout.php" class="flex items-center gap-3 px-4 py-2 text-meta-text hover:text-meta-active transition group">
            <i class="fas fa-sign-out-alt rotate-180 group-hover:text-red-400 transition-colors"></i>
            <span class="font-medium">Sair</span>
        </a>
    </div>
</aside>