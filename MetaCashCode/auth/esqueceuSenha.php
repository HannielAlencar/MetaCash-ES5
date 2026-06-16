<?php
require_once __DIR__ . '/../config.php';
// A lógica de processamento duplicada foi movida/removida daqui, já que o formulário envia para processa-recuperacao.php
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu sua senha? - MetaCash</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/esqueceuSenha.css">
</head>
<body>

<div class="back-link-container">
    <a href="../auth/login.php" class="back-link">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Voltar para o login
    </a>
</div>

    <div class="card">
        <div class="icon-header">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
        </div>

        <h2>Esqueceu sua senha?</h2>
        <p class="subtitle">
            Sem problemas! Digite seu e-mail e enviaremos instruções para redefinir sua senha.
        </p>

        <?php if (($gestor_status = $_GET['status'] ?? '') === 'enviado'): ?>
            <p style="color: #4ade80; text-align: center;">Instruções enviadas com sucesso!</p>
        <?php  endif; ?>
        <?php if (($gestor_erro = $_GET['erro'] ?? '') === 'email_invalido'): ?>
            <p style="color: #f87171; text-align: center;">E-mail inválido.</p>
        <?php  endif; ?>

        <form action="processa-recuperacao.php" method="POST">
            <div class="input-group">
                <label for="email" class="input-label">
                    E-mail cadastrado
                </label>

                <div class="input-wrapper">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="seu@email.com"
                        required
                    >
                </div>
            </div>

            <button
                type="submit"
                id="btn-enviar"
                class="btn-submit"
            >
                Enviar instruções
            </button>
        </form>

        <div class="info-box">
            <p><strong>Importante:</strong> Verifique sua caixa de entrada e a pasta de spam. O e-mail pode levar alguns minutos para chegar.</p>
        </div>
    </div>

    <footer>
        <p>Precisa de ajuda? <a href="#" style="color: #ffffff; text-decoration: underline;">Entre em contato com o suporte</a></p>
        <p>&copy; 2026 <strong>MetaCash</strong>. Todos os direitos reservados.</p>
    </footer>

</body>
</html>