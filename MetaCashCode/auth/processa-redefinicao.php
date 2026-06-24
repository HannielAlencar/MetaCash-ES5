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
    // 1. Gera o hash seguro da nova senha
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

    // 2. Busca na tabela correta (recuperacao_senha) se o token é válido
    $sql_busca = "SELECT id_usuario, id_recuperacao FROM recuperacao_senha 
                  WHERE token_seguro = :token 
                  AND utilizado = false 
                  AND data_expiracao > NOW() 
                  LIMIT 1";
    
    $stmt_busca = $pdo->prepare($sql_busca);
    $stmt_busca->execute([':token' => $token]);
    $recuperacao = $stmt_busca->fetch();

    if (!$recuperacao) {
        header("Location: erro.php");
        exit();
    }

    $id_usuario = $recuperacao['id_usuario'];
    $id_recuperacao = $recuperacao['id_recuperacao'];

    $pdo->beginTransaction();

    // 3. Atualiza a senha na tabela 'usuarios'
    $sql_update_user = "UPDATE usuarios 
                        SET senha = :senha 
                        WHERE id_usuario = :id_usuario";
    $stmt_update_user = $pdo->prepare($sql_update_user);
    $stmt_update_user->execute([
        ':senha'      => $senha_hash,
        ':id_usuario' => $id_usuario
    ]);

    // 4. Marca o token como utilizado na tabela correta (recuperacao_senha)
    $sql_update_token = "UPDATE recuperacao_senha 
                         SET utilizado = true 
                         WHERE id_recuperacao = :id_recuperacao";
    $stmt_update_token = $pdo->prepare($sql_update_token);
    $stmt_update_token->execute([
        ':id_recuperacao' => $id_recuperacao
    ]);

    $pdo->commit();

    header("Location: login.php?status=senha_atualizada");
    exit();

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    header("Location: redefinirSenha.php?token=$token&status=erro_banco");
    exit();
}