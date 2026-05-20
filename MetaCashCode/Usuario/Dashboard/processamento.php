<?php
// Ativa buffer de saída para garantir que o redirecionamento funcione
ob_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $arquivo_db = 'banco.json';
    
    // Pega os dados atuais
    $storage = json_decode(file_get_contents($arquivo_db), true);

    // Nova transação
    $nova_tr = [
        'titulo' => $_POST['titulo'],
        'valor'  => (float)$_POST['valor'],
        'tipo'   => $_POST['tipo'], // 'e' ou 's'
        'cat'    => $_POST['categoria'],
        'data'   => $_POST['data']
    ];

    // Atualiza saldos
    if ($nova_tr['tipo'] == 'e') {
        $storage['saldo_total'] += $nova_tr['valor'];
        $storage['receitas_mes'] += $nova_tr['valor'];
    } else {
        $storage['saldo_total'] -= $nova_tr['valor'];
        $storage['despesas_mes'] += $nova_tr['valor'];
    }

    // Adiciona na lista
    array_unshift($storage['transacoes'], $nova_tr);

    // Salva
    file_put_contents($arquivo_db, json_encode($storage, JSON_PRETTY_PRINT));

    // Redireciona e encerra
    header("Location: index.php");
    exit;
}