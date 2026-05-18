<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_empresa'])) {
    die("Erro: Empresa não identificada.");
}

$tipo_filtro = $_GET['tipo'] ?? 'todos';
$periodo = $_GET['periodo'] ?? 'mensal';
$mes_sel = str_pad($_GET['mes'] ?? date('m'), 2, '0', STR_PAD_LEFT);
$ano_sel = $_GET['ano'] ?? date('Y');

$id_empresa = $_SESSION['id_empresa'];

// Função auxiliar de formatação
if (!function_exists('formatarMoeda')) {
    function formatarMoeda($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }
}

try {
    // Construir a query de transações
    $sql = "SELECT 
                t.id_transacao,
                t.descricao_transacao AS titulo,
                t.valor_transacao AS valor,
                t.tipo_transacao AS tipo,
                c.nome_categoria AS categoria,
                DATE_FORMAT(t.data_registro, '%d/%m/%Y') AS data
            FROM transacoes t
            LEFT JOIN categoria c ON t.id_categoria = c.id_categoria
            WHERE t.id_empresa = :empresa";
    
    // Filtrar por tipo (Receita/Despesa)
    if ($tipo_filtro !== 'todos') {
        $tipo_banco = ($tipo_filtro === 'e') ? 'Receita' : 'Despesa';
        $sql .= " AND t.tipo_transacao = :tipo";
    }
    
    // Filtrar por período
    if ($periodo === 'mensal') {
        $sql .= " AND YEAR(t.data_registro) = :ano AND MONTH(t.data_registro) = :mes";
    } else {
        $sql .= " AND YEAR(t.data_registro) = :ano";
    }
    
    $sql .= " ORDER BY t.data_registro DESC, t.id_transacao DESC";
    
    $stmt = $pdo->prepare($sql);
    $params = [':empresa' => $id_empresa, ':ano' => $ano_sel];
    
    if ($tipo_filtro !== 'todos') {
        $params[':tipo'] = $tipo_banco;
    }
    if ($periodo === 'mensal') {
        $params[':mes'] = $mes_sel;
    }
    
    $stmt->execute($params);
    $transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calcular totais
    $total_receitas = 0;
    $total_despesas = 0;
    
    foreach ($transacoes as $tr) {
        if ($tr['tipo'] === 'Receita') {
            $total_receitas += (float)$tr['valor'];
        } else {
            $total_despesas += (float)$tr['valor'];
        }
    }
    
    $saldo_total = $total_receitas - $total_despesas;
    
} catch (PDOException $e) {
    die("Erro ao buscar transações: " . $e->getMessage());
}

$titulo_periodo = ($periodo === 'mensal') ? "Período: $mes_sel/$ano_sel" : "Período: $ano_sel";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório MetaCash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { 
            .no-print { display: none; }
            body { background: white; }
        }
    </style>
</head>
<body class="p-10 bg-white" onload="window.print()">
    <div class="flex justify-between items-center border-b-2 border-teal-500 pb-5 mb-10">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Relatório Financeiro</h1>
            <p class="text-slate-500"><?= $titulo_periodo ?></p>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-bold text-teal-600">MetaCash</h2>
            <p class="text-xs text-slate-400">Gerado em: <?= date('d/m/Y H:i') ?></p>
        </div>
    </div>

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-slate-100 text-left">
                <th class="p-3 border">Data</th>
                <th class="p-3 border">Título</th>
                <th class="p-3 border">Categoria</th>
                <th class="p-3 border">Tipo</th>
                <th class="p-3 border text-right">Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($transacoes as $tr): ?>
            <tr>
                <td class="p-3 border"><?= $tr['data'] ?></td>
                <td class="p-3 border font-medium"><?= htmlspecialchars($tr['titulo']) ?></td>
                <td class="p-3 border"><?= htmlspecialchars($tr['categoria'] ?? 'Geral') ?></td>
                <td class="p-3 border"><?= ($tr['tipo'] === 'Receita') ? 'Receita' : 'Despesa' ?></td>
                <td class="p-3 border text-right font-bold <?= ($tr['tipo'] === 'Receita') ? 'text-teal-600' : 'text-red-500' ?>">
                    <?= formatarMoeda($tr['valor']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mt-10 p-5 bg-slate-50 rounded-xl flex justify-between gap-10">
        <div class="text-left">
            <p class="text-xs text-slate-500 uppercase font-bold">Total de Receitas</p>
            <p class="text-2xl font-black text-teal-600"><?= formatarMoeda($total_receitas) ?></p>
        </div>
        <div class="text-left">
            <p class="text-xs text-slate-500 uppercase font-bold">Total de Despesas</p>
            <p class="text-2xl font-black text-red-500"><?= formatarMoeda($total_despesas) ?></p>
        </div>
        <div class="text-right">
            <p class="text-xs text-slate-500 uppercase font-bold">Saldo</p>
            <p class="text-2xl font-black <?= $saldo_total >= 0 ? 'text-slate-800' : 'text-red-600' ?>">
                <?= formatarMoeda($saldo_total) ?>
            </p>
        </div>
    </div>

    <button onclick="window.close()" class="no-print mt-10 bg-slate-800 text-white px-6 py-2 rounded-lg hover:bg-slate-700">Fechar</button>
</body>
</html>
