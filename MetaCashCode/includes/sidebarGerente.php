<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Importa a conexão com o banco para buscar as cores dinâmicas
require_once __DIR__ . '/../config.php';

$pagina_atual = strtolower(basename($_SERVER['PHP_SELF']));
$nome_usuario = $_SESSION['nome'] ?? 'Gerente';
$email_usuario = $_SESSION['email'] ?? 'gerente@empresa.com';
$id_empresa = $_SESSION['id_empresa'] ?? null;

$inicial_nome = strtoupper(substr(trim($nome_usuario), 0, 1));

// ==========================================
// LÓGICA DA LOGO DINÂMICA
// ==========================================
// Verifica se há uma logo personalizada salva na sessão. Se não, usa a padrão.
$logoExibida = (isset($_SESSION['logo_path']) && !empty($_SESSION['logo_path'])) 
    ? $_SESSION['logo_path'] 
    : '../assets/img/logo_empresas.png';

// 1. CORES PADRÃO (Caso a empresa não tenha personalizado nada ainda)
$cor_menu     = '#0F2440';
$cor_btn1     = '#204C73';
$cor_destaque = '#24A6B6';
$cor_btn2     = '#35C59A';
$cor_clara    = '#5DA4C0';
$cor_fundo    = '#FDFEFB';

// 2. BUSCA AS CORES DO SEU BANCO DE DADOS
if ($id_empresa && isset($pdo)) {
    try {
        $stmt_cores = $pdo->prepare("SELECT cor_menu, cor_btn1, cor_destaque, cor_btn2, cor_clara, cor_fundo FROM temas WHERE id_empresa = :id_empresa LIMIT 1");
        $stmt_cores->execute([':id_empresa' => $id_empresa]);
        $tema = $stmt_cores->fetch();
        
        if ($tema) {
            $cor_menu     = $tema['cor_menu'] ?? $cor_menu;
            $cor_btn1     = $tema['cor_btn1'] ?? $cor_btn1;
            $cor_destaque = $tema['cor_destaque'] ?? $cor_destaque;
            $cor_btn2     = $tema['cor_btn2'] ?? $cor_btn2;
            $cor_clara    = $tema['cor_clara'] ?? $cor_clara;
            $cor_fundo    = $tema['cor_fundo'] ?? $cor_fundo;
        }
    } catch (PDOException $e) {
        // Se a tabela não existir ou der erro, o sistema ignora e usa as cores padrão acima
    }
}
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
        /* Injeção dinâmica das cores vindas do banco de dados */
        --meta-menu: <?= $cor_menu ?>;
        --meta-btn1: <?= $cor_btn1 ?>;
        --meta-destaque: <?= $cor_destaque ?>;
        --meta-btn2: <?= $cor_btn2 ?>;
        --meta-clara: <?= $cor_clara ?>;
        --meta-fundo: <?= $cor_fundo ?>;
        --meta-text: #94a3b8;   
        --meta-active: #ffffff; 
    }
</style>

<aside class="w-64 bg-meta-menu text-meta-active p-4 flex flex-col fixed h-screen shrink-0 z-40 text-sm">
    <div class="flex items-center gap-3 mb-10 px-2 pt-2">
        <!-- Renderiza a logo dinamicamente usando a variável $logoExibida -->
        <img src="<?= htmlspecialchars($logoExibida) ?>" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-contain bg-white/5" onerror="this.onerror=null; this.src='../DashboardGerente/image_75793b.png';">
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

    <div id="modalRelatorio" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-[60] p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h3 class="text-xl font-bold text-slate-800">Baixar Relatório</h3>
                <button type="button" onclick="toggleModal('modalRelatorio')" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="../app/gerarPDF.php" method="GET" target="_blank" class="space-y-6">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-3">Tipo de Transação</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="e" class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition">Receita</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="s" class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition">Despesa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="todos" checked class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition">Ambos</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-3">Período</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="mensal" checked class="hidden peer" onclick="document.getElementById('campoMesRelatorio').style.display='block'">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition">Mensal</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="anual" class="hidden peer" onclick="document.getElementById('campoMesRelatorio').style.display='none'">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition">Anual</div>
                        </label>
                    </div>
                </div>

                <div id="campoMesRelatorio">
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Mês</label>
                    <select name="mes" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-meta-destaque transition bg-white text-slate-700">
                        <option value="01">Janeiro</option><option value="02">Fevereiro</option>
                        <option value="03">Março</option><option value="04">Abril</option>
                        <option value="05" selected>Maio</option><option value="06">Junho</option>
                        <option value="07">Julho</option><option value="08">Agosto</option>
                        <option value="09">Setembro</option><option value="10">Outubro</option>
                        <option value="11">Novembro</option><option value="12">Dezembro</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Ano</label>
                    <select name="ano" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-meta-destaque transition bg-white text-slate-700">
                        <option value="2026" selected>2026</option>
                        <option value="2025">2025</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-4 border-t">
                    <button type="button" onclick="toggleModal('modalRelatorio')" class="flex-1 py-3 border border-slate-300 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-meta-menu to-meta-destaque text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition">
                        <i class="fas fa-download mr-2"></i> Baixar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

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

<!-- Garante que a função de abrir o Modal do Relatório funcione se chamada dentro da sidebar -->
<script>
    if (typeof toggleModal !== 'function') {
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.toggle('hidden');
                modal.classList.toggle('flex');
            }
        }
    }
</script>