<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';

$current_page = basename($_SERVER['PHP_SELF']);
$id_usuario = $_SESSION['id_usuario'] ?? null;
$nome_usuario = $_SESSION['nome_usuario'] ?? 'Gerente';
$email_usuario = $_SESSION['email_usuario'] ?? 'gerente@exemplo.com';

// Busca dados atualizados do usuário no banco se necessário
if ($id_usuario && ($nome_usuario === 'Gerente' || $email_usuario === 'gerente@exemplo.com')) {
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

// Pega as iniciais para o ícone de perfil
if (function_exists('mb_substr')) {
    $iniciais = mb_strtoupper(mb_substr($nome_usuario, 0, 1));
} else {
    $iniciais = strtoupper(substr($nome_usuario, 0, 1));
}

// Estilos base para os links do menu
$base_nav_class = 'flex items-center gap-3 px-4 py-3 rounded-xl transition ';
$active_class = 'bg-[#2dd4bf] text-white shadow-lg';
$inactive_class = 'text-gray-400 hover:bg-slate-800 hover:text-white';

// Definição das classes ativas dinâmicas
$dashboard_classes   = $base_nav_class . ($current_page === 'dashboardGerente.php' ? $active_class : $inactive_class);
$transacoes_classes  = $base_nav_class . ($current_page === 'transacoesGerente.php' ? $active_class : $inactive_class);
$equipe_classes      = $base_nav_class . ($current_page === 'gerenciaEquipe.php' ? $active_class : $inactive_class);
$paginas_classes     = $base_nav_class . ($current_page === 'gerenciaPaginas.php' ? $active_class : $inactive_class);
$historico_classes   = $base_nav_class . ($current_page === 'historico.php' ? $active_class : $inactive_class);
$configuracao_classes = $base_nav_class . ($current_page === 'configuracao.php' ? $active_class : $inactive_class);
$relatorio_classes   = $base_nav_class . ($current_page === 'relatorio.php' ? $active_class : $inactive_class);
?>

<aside class="w-64 bg-[#0f172a] text-white p-4 flex flex-col fixed h-screen shrink-0 z-40">
    <div class="flex items-center gap-3 mb-10 px-2 pt-2">
        <img src="../assets/img/logoCyano.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover">
        <div class="flex flex-col">
            <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
            <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Gestão Empresarial</span>
        </div>
    </div>

    <nav class="flex-1 space-y-2 overflow-y-auto">
        <a href="../app/dashboardGerente.php" class="<?= $dashboard_classes ?>">
            <i class="fas fa-th-large w-5 text-center"></i><span class="font-medium">Dashboard</span>
        </a>
        
        <a href="../app/transacoesGerente.php" class="<?= $transacoes_classes ?>">
            <i class="fas fa-exchange-alt w-5 text-center"></i><span class="font-medium">Transações</span>
        </a>
        
        <a href="../app/gerenciaEquipe.php" class="<?= $equipe_classes ?>">
            <i class="fas fa-users w-5 text-center"></i><span class="font-medium">Equipe</span>
        </a>

        <a href="../app/gerenciaPaginas.php" class="<?= $paginas_classes ?>">
            <i class="fas fa-globe w-5 text-center"></i><span class="font-medium">Gerenciar Páginas</span>
        </a>
        
        <a href="../app/historico.php" class="<?= $historico_classes ?>">
            <i class="fas fa-history w-5 text-center"></i><span class="font-medium">Histórico</span>
        </a>
        
        <a href="../app/configuracao.php" class="<?= $configuracao_classes ?>">
            <i class="fas fa-cog w-5 text-center"></i><span class="font-medium">Configuração</span>
        </a>
        
        <a href="../app/relatorio.php" class="<?= $relatorio_classes ?>">
            <i class="fas fa-chart-bar w-5 text-center"></i><span class="font-medium">Relatório</span>
        </a>
    </nav>

    <div id="modalRelatorio" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-[60] p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h3 class="text-xl font-bold text-slate-800">Baixar Relatório</h3>
                <button onclick="toggleModal('modalRelatorio')" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="../app/gerarPDF.php" method="GET" target="_blank" class="space-y-6">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-3">Tipo de Transação</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="e" class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Receita</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="s" class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Despesa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="todos" checked class="hidden peer">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Ambos</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-3">Período</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="mensal" checked class="hidden peer" onclick="document.getElementById('campoMesRelatorio').style.display='block'">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Mensal</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="anual" class="hidden peer" onclick="document.getElementById('campoMesRelatorio').style.display='none'">
                            <div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Anual</div>
                        </label>
                    </div>
                </div>

                <div id="campoMesRelatorio">
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Mês</label>
                    <select name="mes" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-teal-500 transition bg-white text-slate-700">
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
                    <select name="ano" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-teal-500 transition bg-white text-slate-700">
                        <option value="2026" selected>2026</option>
                        <option value="2025">2025</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-4 border-t">
                    <button type="button" onclick="toggleModal('modalRelatorio')" class="flex-1 py-3 border border-slate-300 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-slate-800 to-teal-600 text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition">
                        <i class="fas fa-download mr-2"></i> Baixar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-auto pt-6 border-t border-slate-800 space-y-4 pb-2">
        <a href="../app/perfil.php" class="bg-[#1e3a5f]/40 p-3 rounded-2xl flex items-center gap-3 border border-slate-700/50 hover:bg-[#1e3a5f]/60 transition block group">
            <div class="w-10 h-10 bg-[#2dd4bf] rounded-full flex items-center justify-center text-[#0f172a] font-bold text-lg shrink-0 group-hover:scale-105 transition-transform">
                <?= htmlspecialchars($iniciais, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-sm font-bold truncate text-white"><?= htmlspecialchars($nome_usuario, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="text-[10px] text-gray-400 truncate"><?= htmlspecialchars($email_usuario, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </a>
    </div>

    <div class="border-t border-slate-700 pt-4">
        <form method="POST" action="../auth/logout.php">
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition text-left">
                <i class="fas fa-sign-out-alt"></i><span class="font-medium">Sair</span>
            </button>
        </form>
    </div>
</aside>
