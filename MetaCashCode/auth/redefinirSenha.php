<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - MetaCash</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="RedefinirSenha.css/style.css">
</head>
<body>

    <div class="header-icon-top">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>
    </div>

    <h1 class="main-title">Redefinir Senha</h1>
    <p class="main-subtitle">Crie uma nova senha segura</p>

    <div class="card">
        <form action="../Login.php/index.php" method="POST">
            
            <div class="input-group">
                <label class="input-label">Nova Senha</label>
                <div class="input-wrapper">
                    <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input type="password" name="nova_senha" placeholder="Digite sua nova senha" required>
                    <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </div>
            </div>

            <div class="input-group">
                <label class="input-label">Confirmar Nova Senha</label>
                <div class="input-wrapper">
                    <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input type="password" name="confirmar_senha" placeholder="Confirme sua nova senha" required>
                    <svg class="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </div>
            </div>

            <div class="requirements-box">
                <p class="requirements-title">Sua senha deve conter:</p>
                <ul>
                    <li>Pelo menos 8 caracteres</li>
                    <li>Uma letra maiúscula</li>
                    <li>Um número</li>
                    <li>Um caractere especial (recomendado)</li>
                </ul>
            </div>

            <button type="submit" class="btn-submit">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Redefinir Senha
            </button>
        </form>

        <a href="../Login.php/index.php" class="back-to-login-link">Voltar ao login</a>
    </div>

    <footer>
        <p>&copy; 2026 <strong>MetaCash</strong>. Todos os direitos reservados.</p>
    </footer>

</body>
</html>