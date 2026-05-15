<?php

$host = '127.0.0.1'; 
$db   = 'MetaCash';
$user = 'root';
$pass = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opções do PDO para segurança e tratamento de erros
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna os dados como array associativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desativa a emulação para maior segurança
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Conexão com banco MetaCash realizada com sucesso!"; 
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}