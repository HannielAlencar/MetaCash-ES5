<?php
require 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Limpa o e-mail recebido
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    // 2. Prepara e executa a consulta no PostgreSQL
    $stmt = $pdo->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    // 3. Verifica se o usuário existe e se a senha está correta
    if ($usuario && $senha === $usuario['senha']) {
        
        // Salva os dados na sessão para identificar o usuário logado
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nome'] = $usuario['nome'];

        // --- FUNÇÃO DE REDIRECIONAMENTO (SUCESSO) ---
        header("Location: .../Dashboard/index.php");
        exit; // O exit é obrigatório para interromper o script aqui
        
    } else {
        // --- FUNÇÃO DE REDIRECIONAMENTO (ERRO) ---
        // Se a senha estiver errada, volta para o login com um aviso
        header("Location: index.php?erro=1");
        exit;
    }
}
?>