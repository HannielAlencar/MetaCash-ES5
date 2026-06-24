<?php
require_once '../config.php';
session_start();

$id = $_GET['id'];
$novo_cargo = $_GET['novo_cargo'];

$stmt = $pdo->prepare("UPDATE usuarios SET nivel_permissao = ? WHERE id_usuario = ? AND id_empresa = ?");
$stmt->execute([$novo_cargo, $id, $_SESSION['id_empresa']]);

header("Location: ../Usuario/gerenciaEquipe.php");