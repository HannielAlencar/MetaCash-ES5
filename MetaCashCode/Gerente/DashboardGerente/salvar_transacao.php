<?php
$arquivo_db = __DIR__ . '/../../Usuario/Dashboard/banco.json';

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
    $tipo_raw = strtolower(trim($_POST['tipo'] ?? 'entrada'));
    $cat    = $_POST['cat'] ?? 'Geral';
    $data_raw = trim($_POST['data'] ?? '');
    $origem = $_POST['origem'] ?? 'dashboard';

    // Normaliza valores aceitos para garantir consistência entre a página de transações e o JSON
    if ($tipo_raw === 'e') {
        $tipo_raw = 'entrada';
    } elseif ($tipo_raw === 's') {
        $tipo_raw = 'saida';
    }

    $tipo = ($tipo_raw === 'entrada') ? 'entrada' : 'saida';

    // Valida e formata a data enviada
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
    if ($tipo === 'entrada') {
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

    // 5. Adiciona ao final da lista para manter a ordem cronológica correta
    if (!isset($storage['transacoes'])) {
        $storage['transacoes'] = [];
    }
    $storage['transacoes'][] = $nova_tr;

    // 6. Salva o arquivo
    file_put_contents($arquivo_db, json_encode($storage, JSON_PRETTY_PRINT));

    // 7. Redirecionamento Inteligente (AQUI ESTAVA O ERRO)
    // Verifique se a pasta no seu computador se chama Transacoes ou Transações
    if ($origem === 'transacoes') {
        // Redireciona para a pasta sem acento
        header("Location: ../TransaçoesGerente.php/index.php");
    } else {
        header("Location: index.php");
    }
    exit();
}
?>