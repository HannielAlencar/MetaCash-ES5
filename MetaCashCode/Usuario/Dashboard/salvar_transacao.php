<?php
$arquivo_db = 'banco.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Carrega o banco ou inicia do zero
    if (file_exists($arquivo_db)) {
        $json_data = file_get_contents($arquivo_db);
        $storage = json_decode($json_data, true);
    } else {
        $storage = [
            'saldo_total' => 0, 
            'receitas_mes' => 0, 
            'despesas_mes' => 0, 
            'transacoes' => []
        ];
    }

    // 2. Pega os dados do formulário
    $titulo = $_POST['titulo'] ?? 'Sem título';
    $valor  = (float)($_POST['valor'] ?? 0);
    $tipo   = $_POST['tipo'] ?? 'e';
    $cat    = $_POST['cat'] ?? 'Geral';
    $data_raw = trim($_POST['data'] ?? '');
    $origem = $_POST['origem'] ?? 'dashboard';

    $data_obj = null;
    if ($data_raw !== '') {
        $data_obj = DateTime::createFromFormat('Y-m-d', $data_raw);
        if (!$data_obj) {
            $data_obj = DateTime::createFromFormat('d/m/Y', $data_raw);
        }
        if (!$data_obj) {
            $data_obj = date_create($data_raw);
        }
    }
    if (!$data_obj) {
        $data_obj = new DateTime();
    }
    $data = $data_obj->format('d/m/Y');

    // 3. Atualiza os totais
    if ($tipo === 'e') {
        $storage['saldo_total'] += $valor;
        $storage['receitas_mes'] += $valor;
    } else {
        $storage['saldo_total'] -= $valor;
        $storage['despesas_mes'] += $valor;
    }

    // 4. Cria o registro
    $nova_tr = [
        'titulo' => $titulo,
        'valor'  => $valor,
        'tipo'   => $tipo,
        'cat'    => $cat,
        'data'   => $data
    ];

    // 5. Adiciona ao topo da lista
    if (!isset($storage['transacoes'])) {
        $storage['transacoes'] = [];
    }
    array_unshift($storage['transacoes'], $nova_tr);

    // 6. Salva o arquivo
    file_put_contents($arquivo_db, json_encode($storage, JSON_PRETTY_PRINT));

    // 7. Redirecionamento Inteligente (AQUI ESTAVA O ERRO)
    // Verifique se a pasta no seu computador se chama Transacoes ou Transações
    if ($origem === 'transacoes') {
        // Redireciona para a pasta sem acento
        header("Location: ../Transacoes.php/index.php");
    } else {
        header("Location: index.php");
    }
    exit();
}
?>