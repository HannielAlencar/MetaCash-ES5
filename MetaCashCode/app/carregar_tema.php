<?php
// Inicia a sessão caso não esteja ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php'; // Ajuste o caminho para o seu config.php

// Cores e Logo padrão do sistema (caso a empresa ainda não tenha personalizado)
$tema = [
    'cor_menu' => '#1e3a5f',
    'cor_destaque' => '#0ea5e9',
    'cor_fundo' => '#f1f5f9',
    'logo_path' => '../assets/img/logo.png'
];

if (isset($_SESSION['id_empresa'])) {
    try {
        // Busca a configuração da empresa vinculada aos usuários da mesma id_empresa
        $sql = "SELECT cor_menu, cor_destaque, cor_fundo, logo_path 
                FROM empresa_config 
                WHERE id_usuario IN (SELECT id_usuario FROM usuarios WHERE id_empresa = :id_empresa) 
                ORDER BY updated_at DESC LIMIT 1";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_empresa' => $_SESSION['id_empresa']]);
        $config = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se encontrou personalização, substitui os valores padrão
        if ($config) {
            $tema['cor_menu'] = !empty($config['cor_menu']) ? $config['cor_menu'] : $tema['cor_menu'];
            $tema['cor_destaque'] = !empty($config['cor_destaque']) ? $config['cor_destaque'] : $tema['cor_destaque'];
            $tema['cor_fundo'] = !empty($config['cor_fundo']) ? $config['cor_fundo'] : $tema['cor_fundo'];
            $tema['logo_path'] = !empty($config['logo_path']) ? $config['logo_path'] : $tema['logo_path'];
        }
    } catch (PDOException $e) {
        // Erro silencioso para não quebrar a tela do usuário
        error_log("Erro ao carregar tema: " . $e->getMessage());
    }
}
?>

<style>
    :root {
        --cor-menu: <?= htmlspecialchars($tema['cor_menu'], ENT_QUOTES, 'UTF-8') ?>;
        --cor-destaque: <?= htmlspecialchars($tema['cor_destaque'], ENT_QUOTES, 'UTF-8') ?>;
        --cor-fundo: <?= htmlspecialchars($tema['cor_fundo'], ENT_QUOTES, 'UTF-8') ?>;
    }

    /* Como usar as variáveis: você pode aplicar essas classes onde quiser no seu projeto */
    body { background-color: var(--cor-fundo) !important; }
    .bg-menu-personalizado { background-color: var(--cor-menu) !important; }
    .text-destaque-personalizado { color: var(--cor-destaque) !important; }
</style>