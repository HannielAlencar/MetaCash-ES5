<?php
require_once 'config.php';
$stmt = $pdo->query("SELECT * FROM transacoes");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
