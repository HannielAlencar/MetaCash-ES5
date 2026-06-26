<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '../config.php'; // Ajuste o caminho do config.php

// Restringe para que apenas a liderança da empresa possa mudar as cores
if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['nivel_permissao'], ['Gerente', 'Admin'])) {
    die("Acesso negado. Apenas gerentes ou administradores podem alterar o layout.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $id_empresa = $_SESSION['id_empresa'];
    
    // Captura as cores enviadas pelo formulário (ajuste os "names" se necessário)
    $cor_menu = $_POST['cor_menu'] ?? null;
    $cor_destaque = $_POST['cor_destaque'] ?? null;
    $cor_fundo = $_POST['cor_fundo'] ?? null;
    
    // Como 'nome_empresa' é NOT NULL na sua tabela, passamos um nome genérico caso não venha no POST
    $nome_empresa = $_POST['nome_empresa'] ?? "Empresa ID " . $id_empresa; 

    try {
        // Verifica se já existe uma configuração vinculada a esta empresa
        $sqlCheck = "SELECT id FROM empresa_config WHERE id_usuario IN (SELECT id_usuario FROM usuarios WHERE id_empresa = :id_empresa) LIMIT 1";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([':id_empresa' => $id_empresa]);
        $existe = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($existe) {
            // Se já existe, atualiza as cores da empresa (UPDATE)
            $sql = "UPDATE empresa_config 
                    SET cor_menu = :cor_menu, 
                        cor_destaque = :cor_destaque, 
                        cor_fundo = :cor_fundo,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id_config";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':cor_menu' => $cor_menu,
                ':cor_destaque' => $cor_destaque,
                ':cor_fundo' => $cor_fundo,
                ':id_config' => $existe['id']
            ]);
        } else {
            // Se não existe, cria o primeiro registro (INSERT)
            $sql = "INSERT INTO empresa_config (id_usuario, nome_empresa, cor_menu, cor_destaque, cor_fundo) 
                    VALUES (:id_usuario, :nome_empresa, :cor_menu, :cor_destaque, :cor_fundo)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':nome_empresa' => $nome_empresa,
                ':cor_menu' => $cor_menu,
                ':cor_destaque' => $cor_destaque,
                ':cor_fundo' => $cor_fundo
            ]);
        }
        
        // Retorna para a página de configurações com mensagem de sucesso
        header("Location: /app/edicaoTransacoes.php?sucesso=tema_atualizado");
        exit();

    } catch (PDOException $e) {
        die("Erro no banco de dados ao salvar o tema: " . $e->getMessage());
    }
}