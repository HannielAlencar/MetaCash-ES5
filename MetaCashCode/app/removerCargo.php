<?php
require_once '../config.php';
session_start();

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ? AND id_empresa = ?");
$stmt->execute([$id, $_SESSION['id_empresa']]);

header("Location: ../Usuario/gerenciaEquipe.php");