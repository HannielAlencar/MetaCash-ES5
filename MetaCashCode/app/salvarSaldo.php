<?php
require_once '../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empresa = $_SESSION['id_empresa'];
    $novo_saldo = str_replace(',', '.', $_POST['saldo_inicial']); // Converte formato brasileiro para decimal

    try {
        $stmt = $pdo->prepare("UPDATE transacoes SET saldo_inicial = ? WHERE valor_transacao = ?");
        $stmt->execute([$novo_saldo, $id_empresa]);
        
        header("Location: ../app/dashboardGerente.php?sucesso=Saldo atualizado com sucesso!");
        exit();
    } catch (PDOException $e) {
        die("Erro ao salvar saldo: " . $e->getMessage());
    }
}
?>