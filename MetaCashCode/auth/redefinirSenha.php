<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - MetaCash</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/redefinirSenha.css">
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
        <form action="processa-redefinicao.php" method="POST">

            <input
                type="hidden"
                name="token"
                value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>"
            >

            <div class="input-group">
                <label class="input-label">Nova Senha</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        name="nova_senha"
                        required
                    >
                </div>
            </div>

            <div class="input-group">
                <label class="input-label">Confirmar Nova Senha</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        name="confirmar_senha"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Redefinir Senha
            </button>
        </form>
    </div> <footer>
        <p>&copy; 2026 <strong>MetaCash</strong>. Todos os direitos reservados.</p>
    </footer>

</body>
</html>