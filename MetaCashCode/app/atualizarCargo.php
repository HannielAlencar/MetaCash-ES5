<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Trava de segurança: apenas Gerente ou Admin pode rodar esse script
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['nivel_permissao']) || ($_SESSION['nivel_permissao'] !== 'Gerente' && $_SESSION['nivel_permissao'] !== 'Admin')) {
    header("Location: dashboardUsuario.php");
    exit();
}

require_once '../config.php';

// Pega os dados vindos da URL (GET)
$id_usuario_alvo = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$novo_cargo = filter_input(INPUT_GET, 'novo_cargo', FILTER_SANITIZE_SPECIAL_CHARS);
$id_empresa_sessao = $_SESSION['id_empresa'] ?? null;

// Valida se os dados necessários existem e se o cargo é válido
$cargos_permitidos = ['Membro', 'Gerente'];

if ($id_usuario_alvo && $id_empresa_sessao && in_array($novo_cargo, $cargos_permitidos)) {
    try {
        // Segurança extra: Garante que o gerente só altera usuários da PRÓPRIA empresa
        $sql = "UPDATE usuarios SET nivel_permissao = :novo_cargo WHERE id_usuario = :id_usuario AND id_empresa = :id_empresa";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'novo_cargo' => $novo_cargo,
            'id_usuario' => $id_usuario_alvo,
            'id_empresa' => $id_empresa_sessao
        ]);
        
        // Redireciona de volta para a página anterior (ou para a equipe.php)
        // Dica: Se o seu arquivo principal se chamar diferente de 'equipe.php', mude o nome abaixo
        header("Location: gerenciaEquipe.php?sucesso=cargo_atualizado");
        exit();

    } catch (PDOException $e) {
        error_log("Erro ao atualizar cargo: " . $e->getMessage());
        header("Location: gerenciaEquipe.php?erro=banco");
        exit();
    }
} else {
    // Se os dados forem inválidos ou tentarem injetar algo malicioso
    header("Location: gerenciaEquipe.php?erro=dados_invalidos");
    exit();
}