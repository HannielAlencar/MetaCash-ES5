<?php

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

$token = filter_input(
    INPUT_POST,
    'token',
    FILTER_SANITIZE_SPECIAL_CHARS
);

$nova_senha = trim($_POST['nova_senha'] ?? '');
$confirmar_senha = trim($_POST['confirmar_senha'] ?? '');

// Verifica se os campos foram preenchidos
if (empty($nova_senha) || empty($confirmar_senha)) {
    header("Location: redefinirSenha.php?token=$token&status=campos_vazios");
    exit();
}

// Verifica se as senhas coincidem
if ($nova_senha !== $confirmar_senha) {
    header("Location: redefinirSenha.php?token=$token&status=senhas_diferentes");
    exit();
}

// Verifica tamanho mínimo
if (strlen($nova_senha) < 8) {
    header("Location: redefinirSenha.php?token=$token&status=senha_fraca");
    exit();
}

try {
    // Quando integrar com o banco:
    // $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    // atualizar usuário pelo token

    header("Location: login.php?status=senha_atualizada");
    exit();

} catch (Exception $e) {
    header("Location: redefinirSenha.php?token=$token&status=erro_banco");
    exit();
}
