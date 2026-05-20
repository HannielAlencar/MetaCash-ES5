<?php
// shared_data.php
// Carrega os dados financeiros do JSON compartilhado usado por Gerente/TransaçoesGerente.php

$arquivo_db = __DIR__ . '/../Usuario/Dashboard/banco.json';
$default_storage = [
    'saldo_total' => 0,
    'receitas_mes' => 0,
    'despesas_mes' => 0,
    'transacoes' => []
];

$storage = $default_storage;
if (is_file($arquivo_db) && is_readable($arquivo_db)) {
    $conteudo = file_get_contents($arquivo_db);
    $dados_json = json_decode($conteudo, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($dados_json)) {
        $storage = array_merge($default_storage, $dados_json);
    }
}

$transacoes_carga = is_array($storage['transacoes']) ? $storage['transacoes'] : [];
$transacoes = [];
$total_receitas = 0;
$total_despesas = 0;
$categorias_totais = [];
$meses_totais = [];

$meses_pt = [
    1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
    5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
    9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
];

foreach ($transacoes_carga as $transacao) {
    if (!is_array($transacao)) {
        continue;
    }

    $titulo = trim($transacao['titulo'] ?? $transacao['nome'] ?? 'Sem título');
    $categoria = trim($transacao['cat'] ?? 'Geral');
    $tipo_raw = strtolower(trim($transacao['tipo'] ?? 's'));
    if ($tipo_raw === 'entrada' || $tipo_raw === 'e') {
        $tipo = 'e';
    } else {
        $tipo = 's';
    }

    $valor = (float) ($transacao['valor'] ?? 0);
    $data_raw = trim($transacao['data'] ?? '');
    $data_obj = DateTime::createFromFormat('d/m/Y', $data_raw);
    if (!$data_obj) {
        $data_obj = DateTime::createFromFormat('Y-m-d', $data_raw);
    }
    if (!$data_obj) {
        $data_obj = new DateTime();
    }
    $data = $data_obj->format('d/m/Y');
    $mes_key = $data_obj->format('Y-m');
    $mes_label = $meses_pt[(int)$data_obj->format('n')];

    if ($tipo === 'e') {
        $total_receitas += $valor;
        $meses_totais[$mes_key]['receitas'] = ($meses_totais[$mes_key]['receitas'] ?? 0) + $valor;
    } else {
        $total_despesas += $valor;
        $meses_totais[$mes_key]['despesas'] = ($meses_totais[$mes_key]['despesas'] ?? 0) + $valor;
    }

    $categorias_totais[$categoria] = ($categorias_totais[$categoria] ?? 0) + abs($valor);

    $transacoes[] = [
        'titulo' => $titulo,
        'cat' => $categoria,
        'tipo' => $tipo,
        'valor' => $valor,
        'data' => $data,
        'data_obj' => $data_obj,
        'descricao' => $transacao['descricao'] ?? $titulo
    ];
}

usort($transacoes, function ($a, $b) {
    return $b['data_obj']->getTimestamp() <=> $a['data_obj']->getTimestamp();
});

$labels_meses = [];
$dados_receitas = [];
$dados_despesas = [];
$dados_lucro = [];

if (!empty($meses_totais)) {
    ksort($meses_totais);
    $ultimos_meses = array_slice($meses_totais, -7, 7, true);
    foreach ($ultimos_meses as $mes_key => $valores) {
        [$ano, $mes] = explode('-', $mes_key);
        $mes_int = (int)$mes;
        $labels_meses[] = $meses_pt[$mes_int] . '/' . substr($ano, -2);
        $dados_receitas[] = $valores['receitas'] ?? 0;
        $dados_despesas[] = $valores['despesas'] ?? 0;
        $dados_lucro[] = ($valores['receitas'] ?? 0) - ($valores['despesas'] ?? 0);
    }
}

if (empty($labels_meses)) {
    $labels_meses = ['Set', 'Out', 'Nov', 'Dez', 'Jan', 'Fev', 'Mar'];
    $dados_receitas = [0, 0, 0, 0, 0, 0, 0];
    $dados_despesas = [0, 0, 0, 0, 0, 0, 0];
    $dados_lucro = array_map(function ($r, $d) {
        return $r - $d;
    }, $dados_receitas, $dados_despesas);
}

arsort($categorias_totais);
$categorias = $categorias_totais;

$cards = [
    'faturamento' => [
        'valor' => number_format($total_receitas / 1000, 1, ',', '.') . 'k',
        'porcentagem' => '+0.0%',
        'cor' => 'text-teal-500'
    ],
    'entradas' => [
        'valor' => (string)count(array_filter($transacoes, fn($t) => $t['tipo'] === 'e')),
        'porcentagem' => '+0.0%',
        'cor' => 'text-teal-500'
    ],
    'saidas' => [
        'valor' => (string)count(array_filter($transacoes, fn($t) => $t['tipo'] === 's')),
        'porcentagem' => '-0.0%',
        'cor' => 'text-rose-500'
    ],
    'saldo' => [
        'valor' => number_format(($total_receitas - $total_despesas) / 1000, 1, ',', '.') . 'k',
        'porcentagem' => '+0.0%',
        'cor' => 'text-teal-500'
    ]
];

$saldo_real_lucro = $total_receitas - $total_despesas;

$dados = [
    'saldo_total' => $storage['saldo_total'] ?? $saldo_real_lucro,
    'receitas_mes' => $storage['receitas_mes'] ?? $total_receitas,
    'despesas_mes' => $storage['despesas_mes'] ?? $total_despesas
];

$historico_registros = [];
foreach ($transacoes as $tr) {
    $tipo_texto = $tr['tipo'] === 'e' ? 'Receita' : 'Despesa';
    $historico_registros[] = [
        'tag' => 'Transação',
        'tag_color' => 'bg-teal-100 text-teal-600',
        'cat' => $tr['cat'],
        'desc' => sprintf('%s cadastrada: %s (%s)', $tipo_texto, $tr['titulo'], number_format(abs($tr['valor']), 2, ',', '.')),
        'data' => $tr['data']
    ];
}

if (empty($historico_registros)) {
    $historico_registros = [
        ['tag' => 'Criação', 'tag_color' => 'bg-green-100 text-green-600', 'cat' => 'Transação', 'desc' => 'Nova transação de receita: Venda de Produtos', 'data' => '13/03/2026'],
        ['tag' => 'Edição', 'tag_color' => 'bg-blue-100 text-blue-600', 'cat' => 'Configurações', 'desc' => 'Atualização das configurações de empresa', 'data' => '14/03/2026'],
        ['tag' => 'Criação', 'tag_color' => 'bg-green-100 text-green-600', 'cat' => 'Membro da Equipe', 'desc' => 'Novo membro adicionado à equipe: Maria Santos', 'data' => '15/03/2026'],
        ['tag' => 'Edição', 'tag_color' => 'bg-blue-100 text-blue-600', 'cat' => 'Transação', 'desc' => 'Transação editada: Atualização de valor', 'data' => '16/03/2026'],
        ['tag' => 'Exclusão', 'tag_color' => 'bg-red-100 text-red-600', 'cat' => 'Transação', 'desc' => 'Transação excluída: Duplicada', 'data' => '17/03/2026']
    ];
}
