<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['id_empresa'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empresa = $_SESSION['id_empresa'];
    // Tratamento: remove ponto de milhar e substitui vírgula por ponto (Padrão SQL)
    $novo_saldo = str_replace(['.', ','], ['', '.'], $_POST['saldo_inicial']);

    try {
        // A ATUALIZAÇÃO AGORA É NA TABELA EMPRESAS, ONDE O SALDO É ÚNICO
        $stmt = $pdo->prepare("UPDATE empresas SET saldo_inicial = ? WHERE id_empresa = ?");
        $stmt->execute([$novo_saldo, $id_empresa]);
        
        header("Location: ../app/dashboardGerente.php?sucesso=1");
        exit();
    } catch (PDOException $e) {
        error_log("Erro ao atualizar saldo: " . $e->getMessage());
        die("Erro ao salvar saldo: " . $e->getMessage());
    }
}
?>