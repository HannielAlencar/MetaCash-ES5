<?php
include_once("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Captura e protege os dados
    $cpf = $conn->real_escape_string($_POST['cpf']);
    $senha = $_POST['senha'];

    // Busca o usuário pelo CPF
    $sql = "SELECT * FROM gerentes WHERE cpf = '$cpf' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Verifica a senha (ajustado para texto simples ou hash)
        // Se você usa password_hash no cadastro, use: password_verify($senha, $usuario['senha'])
        if ($senha === $usuario['senha']) {
            
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];

            header("Location: dashboard.php"); // Altere para sua página principal
            exit();
        } else {
            echo "<script>alert('Senha incorreta!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('CPF não cadastrado!'); window.location.href='login.php';</script>";
    }

    
    // No processa_login.php, caso precise formatar antes de consultar
$cpf = $_POST['cpf'];
// Se precisar adicionar os pontos para bater com o banco:
// $cpf_formatado = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
}
?>