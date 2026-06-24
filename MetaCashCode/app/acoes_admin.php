<?php
// Inclui o ficheiro de configuração para ter acesso ao $pdo
require_once '../config.php';

// Verifica se os dados chegaram via método POST (como o nosso JavaScript envia)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $id = $_POST['id'] ?? null;

    // Se não enviou um ID válido, devolve erro
    if (!$id) {
        echo json_encode(['sucesso' => false, 'erro' => 'ID não fornecido']);
        exit;
    }

    try {
        if ($acao === 'excluir_usuario') {
            // Apaga o utilizador do banco de dados
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['sucesso' => true]);
            
        } elseif ($acao === 'excluir_empresa') {
            // Apaga a empresa (e consequentemente todos os utilizadores dela devido ao CASCADE)
            $stmt = $pdo->prepare("DELETE FROM empresas WHERE id_empresa = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['sucesso' => true]);
            
        } elseif ($acao === 'desativar_empresa' || $acao === 'ativar_empresa') {
            // Altera o status da empresa
            $novoStatus = ($acao === 'desativar_empresa') ? 'Inativo' : 'Ativa';
            $stmt = $pdo->prepare("UPDATE empresas SET status = :status WHERE id_empresa = :id");
            $stmt->execute([':status' => $novoStatus, ':id' => $id]);
            echo json_encode(['sucesso' => true]);
            
        } else {
            echo json_encode(['sucesso' => false, 'erro' => 'Ação inválida']);
        }
    } catch (PDOException $e) {
        // Se houver algum erro no banco (ex: chave estrangeira), devolve a mensagem de erro
        echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
    }
} else {
    // Se tentarem aceder ao ficheiro diretamente pelo browser, devolve erro
    echo json_encode(['sucesso' => false, 'erro' => 'Método não permitido']);
}
?>