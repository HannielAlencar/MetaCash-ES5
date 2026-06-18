<?php
session_start();
require_once '../config.php';

$id_transacao = $_GET['id'] ?? null;
$id_empresa = $_SESSION['id_empresa'] ?? null;
$id_usuario = $_SESSION['id_usuario'] ?? null;

if ($id_transacao && $id_empresa) {
    try {
        // 1. Opcional: Pegar dados antes de excluir para o log
        $stmt_busca = $pdo->prepare("SELECT descricao_transacao FROM transacoes WHERE id_transacao = ? AND id_empresa = ?");
        $stmt_busca->execute([$id_transacao, $id_empresa]);
        $tr = $stmt_busca->fetch();

        if ($tr) {
            // 2. Excluir
            $stmt = $pdo->prepare("DELETE FROM transacoes WHERE id_transacao = ? AND id_empresa = ?");
            $stmt->execute([$id_transacao, $id_empresa]);

            // 3. Registrar no Histórico
            registrarHistorico($pdo, $id_usuario, 'Exclusão', 'Transação', 'Transação excluída: ' . $tr['descricao_transacao']);
        }
    } catch (PDOException $e) {
        error_log("Erro ao excluir: " . $e->getMessage());
    }
}

header('Location: ../app/transacoesGerente.php');
exit();
?>