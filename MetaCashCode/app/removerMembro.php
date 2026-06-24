<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Trava de segurança: impede que qualquer um delete usuários
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['nivel_permissao']) || ($_SESSION['nivel_permissao'] !== 'Gerente' && $_SESSION['nivel_permissao'] !== 'Admin')) {
    header("Location: dashboardUsuario.php");
    exit();
}

require_once '../config.php';

// Captura e limpa o ID enviado via GET
$id_usuario_alvo = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$id_empresa_sessao = $_SESSION['id_empresa'] ?? null;

if ($id_usuario_alvo && $id_empresa_sessao) {
    
    // Medida de segurança crítica: impede o usuário logado de deletar a si próprio por engano
    if ((int)$id_usuario_alvo === (int)$_SESSION['usuario_id']) {
        header("Location: equipe.php?erro=auto_exclusao");
        exit();
    }

    try {
        // Filtro duplo: Garante que só deleta se pertencer à mesma empresa do administrador logado
        $sql = "DELETE FROM usuarios WHERE id_usuario = :id_usuario AND id_empresa = :id_empresa";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id_usuario' => $id_usuario_alvo,
            'id_empresa' => $id_empresa_sessao
        ]);

        header("Location: gerenciaEquipe.php?sucesso=membro_removido");
        exit();

    } catch (PDOException $e) {
        error_log("Erro ao remover membro da equipe: " . $e->getMessage());
        header("Location: gerenciaEquipe.php?erro=banco");
        exit();
    }
} else {
    header("Location: gerenciaEquipe.php?erro=dados_invalidos");
    exit();
}