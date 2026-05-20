<?php
$host = 'db';
$port = '5432';
$db   = 'metacash';
$user = 'postgres';
$pass = '1234';

$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=prefer";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados PostgreSQL: " . $e->getMessage());
}
?>