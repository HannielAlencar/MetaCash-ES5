<<<<<<< Updated upstream
=======
<?php
// LINHA 1: Inicia a sessão com segurança para evitar quebras se a sidebar depender de dados logados
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. IMPORTA A CONEXÃO COM O BANCO DE DADOS
require_once '../config.php'; 

// Trava de segurança corrigida: impede acesso se não estiver logado OU se não possuir o nível exigido
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['nivel_permissao']) || ($_SESSION['nivel_permissao'] !== 'Gerente' && $_SESSION['nivel_permissao'] !== 'Admin')) {
    header("Location: dashboardUsuario.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_empresa = $_SESSION['id_empresa'];
$config_dashboard_db = 'null';

try {
    $stmt = $pdo->prepare("SELECT config_dashboard FROM configuracoes_paginas WHERE id_empresa = :id");
    $stmt->execute([':id' => $id_empresa]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['config_dashboard'])) {
        $config_dashboard_db = $row['config_dashboard'];
    }
} catch (PDOException $e) {}

$transacoes = [];
$receita = 0;
$despesa = 0;

// Busca categorias personalizadas da empresa E as categorias padrão do sistema
$categorias_banco = [];
try {
    $stmtCat = $pdo->prepare("SELECT nome_categoria FROM categoria WHERE id_empresa = ? OR id_empresa IS NULL OR id_empresa = 0 ORDER BY nome_categoria ASC");
    $stmtCat->execute([$id_empresa]);
    $categorias_banco = $stmtCat->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    // Falha silenciosa caso não consiga buscar categorias
}

date_default_timezone_set('America/Sao_Paulo');

try {
    // --- BUSCA NORMAL RESTAURADA E BLINDADA ---
    $sql = "SELECT 
                t.id_transacao,
                t.descricao_transacao AS titulo, 
                t.valor_transacao AS valor, 
                t.tipo_transacao AS tipo, 
                c.nome_categoria AS cat,
                t.data_transacao,
                t.id_transacao AS id
            FROM transacoes t
            LEFT JOIN categoria c ON t.id_categoria = c.id_categoria
            WHERE t.id_empresa = :empresa
            ORDER BY t.data_transacao DESC, t.id_transacao DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':empresa' => $id_empresa]);
    $transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formata a data direto no PHP
    foreach ($transacoes as &$tr) {
        $tr['data'] = date('d/m/Y', strtotime($tr['data_transacao']));
    }
    unset($tr);

    // Soma os totais
    foreach ($transacoes as $tr) {
        if ($tr['tipo'] === 'Receita') {
            $receita += (float)$tr['valor'];
        } else {
            $despesa += (float)$tr['valor'];
        }
    }
} catch (PDOException $e) {
    error_log("Erro ao buscar transações: " . $e->getMessage());
    echo "<script>console.error('Erro PDO na busca: " . addslashes($e->getMessage()) . "');</script>";
}

$saldoPeriodo = $receita - $despesa;
$receitas_mes = $receita;
$despesas_mes = $despesa;
$saldo_total = $saldoPeriodo;
$transacoes_count = count($transacoes);

if (!function_exists('formatarMoeda')) {
    function formatarMoeda($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }
}
$dados_financeiros = [
    'receitas_mes' => $receita,
    'despesas_mes' => $despesa,
    'saldo_total'  => $saldoPeriodo
];
?>

>>>>>>> Stashed changes
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MetaCash - Gestão</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/transacoesGerente.css">
    <style>
        .text-teal { color: #2dd4bf; }
        .text-red { color: #f87171; }
        .bg-custom-dark { background-color: #0f172a; }
    </style>
</head>
<body class="bg-gray-50">

    <div class="flex">
<aside class="flex flex-col min-h-screen w-64 bg-[#0f172a] text-white p-4">
    <div class="flex items-center gap-3 mb-8 px-2">
        <div class="bg-[#2dd4bf] p-2 rounded-lg text-[#0f172a]">
            <i class="fas fa-chart-line text-xl"></i>
        </div>
        <div class="flex flex-col">
            <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
            <span class="text-[10px] text-gray-400">Gestão Empresarial</span>
        </div>
    </div>

    <nav class="flex-1 space-y-1">
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
            <i class="fas fa-th-large w-5"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#29aeb0] text-white shadow-lg">
            <i class="fas fa-exchange-alt w-5"></i>
            <span class="font-medium">Transações</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
            <i class="fas fa-file-alt w-5"></i>
            <span class="font-medium">Relatórios</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
            <i class="fas fa-users w-5"></i>
            <span class="font-medium">Equipe</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
            <i class="fas fa-dollar-sign w-5"></i>
            <span class="font-medium">Dados Financeiros</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
            <i class="fas fa-history w-5"></i>
            <span class="font-medium">Histórico</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
            <i class="fas fa-cog w-5"></i>
            <span class="font-medium">Configurações</span>
        </a>
    </nav>

    <div class="mt-auto pt-6 border-t border-slate-800">
        <div class="flex items-center gap-3 p-3 bg-[#1e3a5f]/50 rounded-xl mb-4">
            <div class="w-10 h-10 min-w-[40px] rounded-full bg-[#2dd4bf] flex items-center justify-center text-[#0f172a] font-bold">
                E
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-semibold truncate text-white">Empresa LTDA</p>
                <p class="text-[10px] text-gray-400 truncate">empresa@exemplo.com</p>
            </div>
        </div>
        
        <button class="flex items-center gap-3 px-4 py-2 text-gray-400 hover:text-white transition w-full">
            <i class="fas fa-sign-out-alt w-5 text-left"></i>
            <span class="font-medium">Sair</span>
        </button>
    </div>
</aside>

        <main class="flex-1 p-10">
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800">Transações</h1>
                    <p class="text-slate-500">Gerencie todas as transações financeiras</p>
                </div>
                <button class="bg-gradient-to-r from-slate-800 to-teal-600 text-white px-6 py-3 rounded-lg font-bold shadow-lg hover:opacity-90 transition">
                    + Adicionar Transação
                </button>
            </header>

            <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-gray-500 text-sm font-medium mb-2">Total de Receitas</p>
                    <h2 class="text-3xl font-bold text-[#2dd4bf]">R$ 95.500</h2>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-gray-500 text-sm font-medium mb-2">Total de Despesas</p>
                    <h2 class="text-3xl font-bold text-red-400">R$ 66.700</h2>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-gray-500 text-sm font-medium mb-2">Saldo do Período</p>
                    <h2 class="text-3xl font-bold text-slate-800">R$ 28.800</h2>
                </div>
            </section>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800">Transações Recentes</h3>
                    <a href="#" class="text-[#2dd4bf] text-sm font-bold hover:underline">Ver todas</a>
                </div>

                <div class="divide-y divide-gray-50">
                    <?php
                    $dados = [
                        ['label' => 'Venda Cliente XYZ', 'sub' => 'Salário • 01/03/2026', 'valor' => 45000.00, 'icon' => 'fa-arrow-up'],
                        ['label' => 'Fornecedor ABC', 'sub' => 'Compras • 02/03/2026', 'valor' => -15000.00, 'icon' => 'fa-shopping-cart'],
                        ['label' => 'Aluguel Escritório', 'sub' => 'Aluguel • 05/03/2026', 'valor' => -8500.00, 'icon' => 'fa-home'],
                        ['label' => 'Venda Cliente LMN', 'sub' => 'Salário • 09/03/2026', 'valor' => 32000.00, 'icon' => 'fa-arrow-up'],
                        // Adicione mais itens aqui para testar a rolagem
                    ];

                    foreach ($dados as $item):
                        $isPositive = $item['valor'] > 0;
                    ?>
                    <div class="flex justify-between items-center p-6 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-slate-500 text-sm">
                                <i class="fas <?= $item['icon'] ?>"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800"><?= $item['label'] ?></p>
                                <p class="text-xs text-slate-400"><?= $item['sub'] ?></p>
                            </div>
                        </div>
                        <div class="font-bold <?= $isPositive ? 'text-[#2dd4bf]' : 'text-red-400' ?>">
                            <?= ($isPositive ? '+' : '-') . ' R$ ' . number_format(abs($item['valor']), 2, ',', '.') ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

<<<<<<< Updated upstream
=======
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
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Receita</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="s" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Despesa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="todos" checked class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Ambos</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Período</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="mensal" checked class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Mensal</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="periodo" value="anual" class="hidden peer">
                            <div class="text-sm font-semibold text-center py-3 rounded-xl border border-blue-50 bg-blue-50/50 text-blue-600 peer-checked:bg-meta-menu peer-checked:text-white transition-all">Anual</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase block mb-3 tracking-widest">Mês</label>
                    <select name="mes" class="w-full p-4 rounded-2xl border border-slate-200 bg-white text-slate-700 font-medium appearance-none focus:outline-none focus:ring-2 focus:ring-meta-destaque/20 transition-all cursor-pointer">
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
                    <select name="ano" class="w-full p-4 rounded-2xl border border-slate-200 bg-white text-slate-700 font-medium appearance-none focus:outline-none focus:ring-2 focus:ring-meta-destaque/20 transition-all cursor-pointer">
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026" selected>2026</option>
                    </select>
                </div>

                <div class="flex gap-4 pt-6 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('modalRelatorio')" class="flex-1 py-4 border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all">Cancelar</button>
                    <button type="submit" class="flex-1 py-4 bg-meta-destaque hover:opacity-90 text-white font-bold rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-download"></i> Baixar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="popupSucesso" class="fixed top-5 left-1/2 -translate-x-1/2 bg-teal-500 text-white px-6 py-3 rounded-xl shadow-lg hidden z-[100] font-bold" style="display: none;">
        Transação cadastrada com sucesso
    </div>

    <script>
        const configSalvaNoBanco = <?= $config_dashboard_db ?>;

        if (configSalvaNoBanco) {
            // Aqui você pega o JSON e aplica nas classes do Tailwind ou estilos da página
            // Ex: escondendo as divs que o gerente marcou como "visivel: false"
            aplicarConfiguracoesNaTela(configSalvaNoBanco);
        }
    </script>
    <script src="../assets/js/transacoes.js"></script> 
>>>>>>> Stashed changes
</body>
</html>
