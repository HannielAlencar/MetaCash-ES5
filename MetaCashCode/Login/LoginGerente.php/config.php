<?php
$host = "localhost";
$user = "root";       // Usuário padrão do MySQL
$pass = "0369";           // Senha padrão (geralmente vazia no localhost)
$db   = "metacash_db"; // Nome do seu banco de dados

// Cria a conexão
$conn = new mysqli($host, $user, $pass, $db);

// Verifica se houve erro
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>