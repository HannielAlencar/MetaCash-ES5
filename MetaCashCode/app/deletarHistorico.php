<?php
session_start();
require_once '../config.php';

header('Content-Type: application/json');

// Verifica se o utilizador tem permissão
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Não autorizado. Faça login novamente.']);
    exit;
}

// Recebe os dados do fetch (JSON)
$dados = json_decode(file_get_contents("php://input"), true);
$id_historico = $dados['id'] ?? null;

if ($id_historico) {
    try {
        // Deleta garantindo que o registo pertence àquele usuário (medida de segurança)
        $stmt = $pdo->prepare("DELETE FROM historico WHERE id_historico = :id AND usuario_id = :usuario_id");
        $stmt->execute([
            ':id' => $id_historico,
            ':usuario_id' => $_SESSION['usuario_id']
        ]);
        
        echo json_encode(['sucesso' => true]);
    } catch (PDOException $e) {
        echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
    }
} else {
    echo json_encode(['sucesso' => false, 'erro' => 'ID do registo não foi enviado.']);
}
?>