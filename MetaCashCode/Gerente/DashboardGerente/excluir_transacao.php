<?php
/**
 * SISTEMA METACASH - EXCLUSÃO DE TRANSAÇÕES
 * Yuri Henrique - Software Engineering 2026
 */

$arquivo_db = __DIR__ . '/../../Usuario/Dashboard/banco.json';
$redirect = $_GET['redirect'] ?? 'index.php';

// Verifica se o ID foi passado e se o banco existe
if (isset($_GET['id']) && file_exists($arquivo_db)) {
    $id = (int)$_GET['id']; // Garante que o ID seja um número inteiro
    $storage = json_decode(file_get_contents($arquivo_db), true);

    // Verifica se a transação específica existe no array
    if (isset($storage['transacoes'][$id])) {
        $tr = $storage['transacoes'][$id];
        $valor = (float)$tr['valor'];

        /**
         * REVERSÃO DOS VALORES NO SALDO
         * Se era uma Entrada (e), subtraímos do saldo.
         * Se era uma Saída (s), somamos de volta ao saldo.
         */
        if ($tr['tipo'] === 'e') {
            $storage['saldo_total'] = ($storage['saldo_total'] ?? 0) - $valor;
            $storage['receitas_mes'] = ($storage['receitas_mes'] ?? 0) - $valor;
        } else {
            $storage['saldo_total'] = ($storage['saldo_total'] ?? 0) + $valor;
            $storage['despesas_mes'] = ($storage['despesas_mes'] ?? 0) - $valor;
        }

        // Remove a transação selecionada
        unset($storage['transacoes'][$id]);
        
        // REINDEXAÇÃO: Essencial para que o próximo "apagar" não use um índice que não existe mais
        $storage['transacoes'] = array_values($storage['transacoes']);

        // Salva as alterações de volta no banco.json
        file_put_contents($arquivo_db, json_encode($storage, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

// Redireciona para a página informada (ou para o dashboard principal)
header("Location: " . $redirect);
exit();