<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empresa = $_SESSION['id_empresa'] ?? null;
    $id_usuario = $_SESSION['id_usuario'] ?? null;

    if (!$id_empresa || !$id_usuario) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'erro', 'mensagem' => 'Sessão inválida']);
        exit();
    }

    $titulo = trim($_POST['titulo'] ?? 'Sem titulo');
    $valor = (float)($_POST['valor'] ?? 0);
    $tipo_raw = $_POST['tipo'] ?? 'e';
    $cat = trim($_POST['cat'] ?? 'Geral');
    $data_raw = trim($_POST['data'] ?? '');

    $tipo = $tipo_raw === 's' || $tipo_raw === 'Despesa' ? 'Despesa' : 'Receita';

    $data_obj = DateTime::createFromFormat('Y-m-d', $data_raw);
    if (!$data_obj) {
        $data_obj = new DateTime();
    }
    $data_transacao = $data_obj->format('Y-m-d');

    if ($valor <= 0) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'erro', 'mensagem' => 'Valor inválido']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        $sql_categoria = "SELECT id_categoria FROM categoria WHERE id_empresa = :empresa AND nome_categoria = :nome AND tipo_categoria = :tipo LIMIT 1";
        $stmt_categoria = $pdo->prepare($sql_categoria);
        $stmt_categoria->execute([
            ':empresa' => $id_empresa,
            ':nome' => $cat,
            ':tipo' => $tipo
        ]);
        $categoria = $stmt_categoria->fetch();

        if ($categoria) {
            $id_categoria = (int)$categoria['id_categoria'];
        } else {
            $sql_nova_categoria = "INSERT INTO categoria (id_empresa, nome_categoria, tipo_categoria) VALUES (:empresa, :nome, :tipo)";
            $stmt_nova_categoria = $pdo->prepare($sql_nova_categoria);
            $stmt_nova_categoria->execute([
                ':empresa' => $id_empresa,
                ':nome' => $cat,
                ':tipo' => $tipo
            ]);
            $id_categoria = (int)$pdo->lastInsertId();
        }

        $sql_transacao = "INSERT INTO transacoes (id_empresa, id_usuario, id_categoria, tipo_transacao, valor_transacao, data_transacao, descricao_transacao) VALUES (:empresa, :usuario, :categoria, :tipo, :valor, :data, :descricao)";
        $stmt_transacao = $pdo->prepare($sql_transacao);
        $stmt_transacao->execute([
            ':empresa' => $id_empresa,
            ':usuario' => $id_usuario,
            ':categoria' => $id_categoria,
            ':tipo' => $tipo,
            ':valor' => $valor,
            ':data' => $data_transacao,
            ':descricao' => $titulo
        ]);

        // REGISTRA NO HISTÓRICO (não quebra se falhar)
        $hist_ok = false;
        $hist_err = '';
        try {
            $hist_ok = registrarHistorico(
                $pdo,
                $id_usuario,
                'Criação',
                $tipo,
                "Nova transação de {$tipo} criada: '{$titulo}' no valor de R$ " . number_format($valor, 2, ',', '.')
            );
        } catch (Exception $e) {
            $hist_err = $e->getMessage();
            error_log("Erro ao registrar histórico (não afeta salvamento): " . $e->getMessage());
        }

        // Try to get last insert ID
        $id_inserido = -1;
        try {
            $id_inserido = $pdo->lastInsertId();
        } catch (Exception $e) { }

        $pdo->commit();

        header('Content-Type: application/json');
        echo json_encode(['status' => 'sucesso', 'mensagem' => 'Transação salva com sucesso', 'debug' => ['id' => $id_inserido, 'emp' => $id_empresa, 'usr' => $id_usuario, 'hist_ok' => $hist_ok, 'hist_err' => $hist_err]]);
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log('Erro ao salvar transacao: ' . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar transação']);
        exit();
    }
}
header('Content-Type: application/json');
echo json_encode(['status' => 'erro', 'mensagem' => 'Método não permitido']);
?>