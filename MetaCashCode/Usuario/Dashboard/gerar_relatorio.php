<?php
// 1. Configurações Iniciais
ini_set('display_errors', 0);
date_default_timezone_set('America/Sao_Paulo');

$arquivo_banco = __DIR__ . '/banco.json';
if (!file_exists($arquivo_banco)) {
    die("ERRO: O arquivo banco.json não foi encontrado na pasta: " . __DIR__);
}

$data = json_decode(file_get_contents($arquivo_banco), true);
if ($data === null) {
    die("ERRO: Falha ao carregar os dados do arquivo banco.json");
}

$transacoes = [];
foreach ((array)($data['transacoes'] ?? []) as $tr) {
    $tipo = strtolower(trim($tr['tipo'] ?? ''));
    $tipo = ($tipo === 'entrada' || $tipo === 'e') ? 'e' : 's';
    $tr['tipo'] = $tipo;
    $transacoes[] = $tr;
}

// 2. Captura e Tratamento de Filtros
$formato      = $_GET['formato']      ?? 'pdf';
$tipo_filtro  = $_GET['tipo']         ?? 'ambos';
$periodo_tipo = $_GET['periodo_tipo'] ?? 'mensal';

// Garantimos que o mês e ano sejam comparados como números ou strings limpas
$mes_filtro   = isset($_GET['mes']) ? str_pad($_GET['mes'], 2, '0', STR_PAD_LEFT) : date('m');
$ano_filtro   = $_GET['ano'] ?? date('Y');

$listaParaExportar = isset($transacoes) ? $transacoes : [];

// 3. Lógica de Filtragem Refinada
$listaFiltrada = array_filter($listaParaExportar, function($tr) use ($tipo_filtro, $periodo_tipo, $mes_filtro, $ano_filtro) {
    // Filtro de Tipo
    if ($tipo_filtro === 'receita' && ($tr['tipo'] ?? '') !== 'e') return false;
    if ($tipo_filtro === 'despesa' && ($tr['tipo'] ?? '') !== 's') return false;

    // Tratamento da Data Brasileira (dd/mm/aaaa)
    if (empty($tr['data'])) return false;
    $partesData = explode('/', $tr['data']);
    if (count($partesData) < 3) return false;

    $mesTr = str_pad($partesData[1], 2, '0', STR_PAD_LEFT);
    $anoTr = $partesData[2];

    if ($periodo_tipo === 'mensal') {
        return ($anoTr == $ano_filtro && $mesTr == $mes_filtro);
    } else {
        return ($anoTr == $ano_filtro);
    }
});

// Ordenar por data (mais recente primeiro)
usort($listaFiltrada, function($a, $b) {
    return strtotime(str_replace('/', '-', $b['data'])) - strtotime(str_replace('/', '-', $a['data']));
});

// 4. Exportação CSV
if ($formato === 'csv') {
    if (ob_get_length()) ob_end_clean();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="relatorio_metacash_' . date('d-m-Y') . '.csv"');
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    fputcsv($output, ['Data', 'Título', 'Categoria', 'Tipo', 'Valor'], ';');

    foreach ($listaFiltrada as $tr) {
        fputcsv($output, [
            $tr['data'],
            $tr['titulo'] ?? 'Sem título',
            $tr['cat'] ?? 'Geral',
            (($tr['tipo'] ?? '') == 'e' ? 'Receita' : 'Despesa'),
            number_format(($tr['valor'] ?? 0), 2, ',', '.')
        ], ';');
    }
    fclose($output);
    exit;
} 

// 5. Layout do Relatório (PDF/Impressão)
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MetaCash - Relatório</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-50 p-6 md:p-12">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-[2rem] shadow-xl border border-slate-100">
        
        <div class="flex justify-between items-start border-b-2 border-slate-50 pb-8 mb-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-[#0f172a] p-2 rounded-lg text-[#2dd4bf]">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <h1 class="text-3xl font-black text-[#0f172a] tracking-tighter">MetaCash</h1>
                </div>
                <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">Relatório Financeiro Detalhado</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Período</p>
                <p class="text-slate-800 font-bold">
                    <?php echo $periodo_tipo === 'mensal' ? "Mês $mes_filtro / $ano_filtro" : "Ano de $ano_filtro"; ?>
                </p>
            </div>
        </div>

        <table class="w-full mb-8">
            <thead>
                <tr class="text-left text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100">
                    <th class="pb-4">Data</th>
                    <th class="pb-4">Descrição</th>
                    <th class="pb-4">Categoria</th>
                    <th class="pb-4 text-right">Valor</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if (empty($listaFiltrada)): ?>
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <i class="fas fa-folder-open text-slate-200 text-5xl mb-4 block"></i>
                            <p class="text-slate-400 font-medium">Nenhuma transação encontrada para este período.</p>
                            <p class="text-slate-300 text-sm">Verifique se o mês selecionado possui registros.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($listaFiltrada as $tr): ?>
                    <tr>
                        <td class="py-4 text-sm text-slate-500"><?php echo $tr['data']; ?></td>
                        <td class="py-4 font-bold text-slate-700"><?php echo $tr['titulo'] ?? 'Sem título'; ?></td>
                        <td class="py-4 text-xs font-bold text-slate-400 uppercase"><?php echo $tr['cat'] ?? 'Geral'; ?></td>
                        <td class="py-4 text-right font-black <?php echo ($tr['tipo'] ?? '') == 'e' ? 'text-teal-500' : 'text-rose-500'; ?>">
                            <?php echo (($tr['tipo'] ?? '') == 'e' ? '+' : '-') . ' R$ ' . number_format(($tr['valor'] ?? 0), 2, ',', '.'); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="flex justify-between items-center no-print mt-12 bg-slate-900 p-6 rounded-2xl text-white">
            <p class="text-sm text-slate-400">Total de registros: <b><?php echo count($listaFiltrada); ?></b></p>
            <div class="flex gap-3">
                <a href="index.php" class="px-6 py-3 border border-slate-700 rounded-xl font-bold hover:bg-slate-800 transition">Voltar</a>
                <button onclick="window.print()" class="bg-[#2dd4bf] text-[#0f172a] px-8 py-3 rounded-xl font-black shadow-lg hover:bg-teal-400 transition">
                    <i class="fas fa-print mr-2"></i> IMPRIMIR
                </button>
            </div>
        </div>
    </div>

    <script>
        window.onload = () => {
            if(<?php echo count($listaFiltrada); ?> > 0) {
                setTimeout(() => { window.print(); }, 500);
            }
        }
    </script>
</body>
</html>