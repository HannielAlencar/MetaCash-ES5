<?php
session_start();
require_once __DIR__ . '/../config.php';

// Recebe os dados simples via POST (agora não é JSON)
$cor_menu = $_POST['cor_menu'] ?? '#0F2440';
$cor_destaque = $_POST['cor_destaque'] ?? '#24A6B6';
$cor_fundo = $_POST['cor_fundo'] ?? '#FDFEFB';
$cnpj = $_SESSION['cnpj_empresa']; // Garanta que o CNPJ está na sessão no login

$stmt = $pdo->prepare("
    UPDATE empresa_config 
    SET cor_menu = ?, cor_destaque = ?, cor_fundo = ? 
    WHERE cnpj = ?
");
$stmt->execute([$cor_menu, $cor_destaque, $cor_fundo, $cnpj]);

echo "Configuração salva com sucesso!";
?>