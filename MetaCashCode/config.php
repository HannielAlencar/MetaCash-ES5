<?php
// Configurações do PostgreSQL no Docker
$host = 'db';                // AQUI É O SEGREDO: O nome do container do banco
$port = '5432';              // Porta padrão do Postgres
$db   = 'metacash';          // Nome do banco criado no docker-compose
$user = 'postgres';          // Usuário padrão
$pass = '1234';   // A senha exata que você colocou no docker-compose.yml

$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=prefer";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
     // echo "Conexão com PostgreSQL realizada com sucesso!"; 
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados PostgreSQL: " . $e->getMessage());
}
?>