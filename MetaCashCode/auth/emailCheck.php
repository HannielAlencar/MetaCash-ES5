<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-mail Enviado - MetaCash</title>
    <link rel="stylesheet" href="../assets/css/emailCheck.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="background-overlay">
        <header class="top-nav">
            <a href="../auth/login.php" class="back-link">← Voltar ao login</a>
        </header>

        <main class="container">
            <div class="card">
                <div class="icon-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>

                <h1>E-mail enviado!</h1>
                
                <p class="description">
                    Enviamos instruções para redefinir sua senha para:<br>
                    <strong><?php echo "emailexemplo@gmail.com"; ?></strong>
                </p>

                <div class="steps-box">
                    <h3>Próximos passos:</h3>
                    <ul>
                        <li>Verifique sua caixa de entrada</li>
                        <li>Clique no link de redefinição de senha</li>
                        <li>Crie uma nova senha segura</li>
                        <li>Faça login com suas novas credenciais</li>
                    </ul>
                </div>

                <a href="../auth/login.php" class="btn-primary">Voltar ao login</a>

                <p class="resend-text">
                    Não recebeu? <a href="#">Enviar novamente</a>
                </p>
            </div>

            <footer class="footer-help">
                <p>Precisa de ajuda? <a href="#">Entre em contato com o suporte</a></p>
                <p class="copyright">© 2026 MetaCash. Todos os direitos reservados.</p>
            </footer>
        </main>
    </div>

</body>
</html>