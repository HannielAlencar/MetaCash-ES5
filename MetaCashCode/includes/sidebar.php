<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';

$current_page = strtolower(basename($_SERVER['PHP_SELF']));
$id_usuario = $_SESSION['id_usuario'] ?? null;
$nome_usuario = $_SESSION['nome_usuario'] ?? 'Usuario';
$email_usuario = $_SESSION['email_usuario'] ?? 'usuario@exemplo.com';

if ($id_usuario && ($nome_usuario === 'Usuario' || $email_usuario === 'usuario@exemplo.com')) {
    try {
        $stmt = $pdo->prepare('SELECT nome_completo, email FROM usuarios WHERE id_usuario = :id LIMIT 1');
        $stmt->execute([':id' => $id_usuario]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            $nome_usuario = $usuario['nome_completo'] ?: $nome_usuario;
            $email_usuario = $usuario['email'] ?: $email_usuario;
        }
    } catch (PDOException $e) {
        error_log('Erro ao carregar usuario do sidebar: ' . $e->getMessage());
    }
}

if (function_exists('mb_substr')) {
    $iniciais = mb_strtoupper(mb_substr($nome_usuario, 0, 1));
} else {
    $iniciais = strtoupper(substr($nome_usuario, 0, 1));
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
        --meta-menu: #0F2440;      /* Fundo Azul Escuro */
        --meta-btn1: #204C73;      /* Painel do Perfil */
        --meta-destaque: #24A6B6;  /* Azul Piscina Ativo */
        --meta-btn2: #35C59A;     
        --meta-clara: #5DA4C0;    
        --meta-fundo: #FDFEFB;    
        --meta-text: #94a3b8;      /* Texto inativo */
        --meta-active: #ffffff;    /* Texto ativo */
    }
</style>

<aside class="w-64 bg-meta-menu text-meta-active p-4 flex flex-col fixed h-screen shrink-0 z-40 text-sm">
    <div class="flex items-center gap-3 mb-10 px-2 pt-2">
        <img src="../assets/img/logoCyano.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover">
        <div class="flex flex-col">
            <span class="font-bold text-xl leading-tight text-meta-active">MetaCash</span>
            <span class="text-[10px] text-meta-text uppercase tracking-wider font-semibold">Gestão Empresarial</span>
        </div>
    </div>

    <nav class="flex-1 space-y-2">
        <?php 
        // Links corrigidos para navegação interna na pasta 'app'
        $links = [
            ['url' => 'dashboardUsuario.php', 'icon' => 'fa-th-large', 'label' => 'Dashboard', 'file' => 'dashboardusuario.php'],
            ['url' => 'transacoes.php', 'icon' => 'fa-exchange-alt', 'label' => 'Transações', 'file' => 'transacoes.php']
        ];

        foreach ($links as $link) {
            $ativo = ($current_page === $link['file']);
            $classes = $ativo 
                ? 'bg-meta-destaque text-meta-active shadow-lg font-semibold' 
                : 'text-meta-text hover:bg-white/10 hover:text-meta-active';
            
            echo '<a href="'.$link['url'].'" class="flex items-center gap-3 px-4 py-3 rounded-xl transition '.$classes.'">
                    <i class="fas '.$link['icon'].' w-5"></i>
                    <span class="font-medium">'.$link['label'].'</span>
                  </a>';
        }
        ?>

        <button onclick="toggleModal('modalRelatorio')" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-meta-text hover:bg-white/10 hover:text-meta-active transition border border-transparent hover:border-slate-700 text-left">
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
        <a href="../app/perfil.php" class="bg-meta-btn1/40 p-3 rounded-2xl flex items-center gap-3 border border-white/10 hover:bg-meta-btn1/60 transition block group">
            <div class="w-10 h-10 bg-meta-destaque rounded-full flex items-center justify-center text-meta-menu font-bold text-lg shrink-0 group-hover:scale-105 transition-transform">
                <?= htmlspecialchars($iniciais, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-sm font-bold truncate text-meta-active"><?= htmlspecialchars($nome_usuario, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="text-[10px] text-meta-text truncate"><?= htmlspecialchars($email_usuario, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </a>
    </div>

    <div class="border-t border-white/10 pt-4">
        <form method="POST" action="../auth/logout.php">
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-meta-text hover:text-meta-active transition text-left group">
                <i class="fas fa-sign-out-alt rotate-180 group-hover:text-red-400 transition-colors"></i>
                <span class="font-medium">Sair</span>
            </button>
        </form>
    </div>
</aside>