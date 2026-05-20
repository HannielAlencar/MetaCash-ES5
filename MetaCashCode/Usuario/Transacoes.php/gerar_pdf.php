<?php
include 'logica_dados.php';

$tipo_filtro = $_GET['tipo'] ?? 'todos';
$periodo = $_GET['periodo'] ?? 'mensal';
$mes_sel = $_GET['mes'] ?? date('m');
$ano_sel = $_GET['ano'] ?? date('Y');

// Filtrar as transações conforme a escolha do modal
$filtradas = array_filter($transacoes, function($t) use ($tipo_filtro, $periodo, $mes_sel, $ano_sel) {
    $data_t = DateTime::createFromFormat('d/m/Y', $t['data']);
    if (!$data_t) return false;
    
    $match_tipo = ($tipo_filtro === 'todos' || $t['tipo'] === $tipo_filtro);
    $match_ano = ($data_t->format('Y') === $ano_sel);
    $match_mes = ($periodo === 'anual' || $data_t->format('m') === $mes_sel);
    
    return $match_tipo && $match_ano && $match_mes;
});
?>
<!DOCTYPE html>
<html>
<head>
    <title>Relatório MetaCash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="p-10 bg-white" onload="window.print()">
    <div class="flex justify-between items-center border-b-2 border-teal-500 pb-5 mb-10">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Relatório Financeiro</h1>
            <p class="text-slate-500">Período: <?= $periodo == 'mensal' ? "$mes_sel/$ano_sel" : $ano_sel ?></p>
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
            <?php foreach($filtradas as $tr): ?>
            <tr>
                <td class="p-3 border"><?= $tr['data'] ?></td>
                <td class="p-3 border font-medium"><?= $tr['titulo'] ?></td>
                <td class="p-3 border"><?= $tr['cat'] ?></td>
                <td class="p-3 border"><?= $tr['tipo'] == 'e' ? 'Entrada' : 'Saída' ?></td>
                <td class="p-3 border text-right font-bold <?= $tr['tipo'] == 'e' ? 'text-teal-600' : 'text-red-500' ?>">
                    <?= formatarMoeda($tr['valor']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mt-10 p-5 bg-slate-50 rounded-xl flex justify-end gap-10">
        <div class="text-right">
            <p class="text-xs text-slate-500 uppercase font-bold">Total do Período</p>
            <p class="text-2xl font-black text-slate-800">
                <?= formatarMoeda(array_sum(array_column($filtradas, 'valor'))) ?>
            </p>
        </div>
    </div>

    <button onclick="window.close()" class="no-print mt-10 bg-slate-800 text-white px-6 py-2 rounded-lg">Voltar ao Sistema</button>
</body>
</html>