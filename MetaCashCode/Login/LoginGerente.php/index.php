<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Login de Gerente</title>
    <link rel="stylesheet" href="Gerente.css/style.css">
</head>
<body>
    <div class="main-container">
        <header class="header-section">
            <img src="img/logo.png" alt="MetaCash Logo" class="logo">
            <h1>MetaCash</h1>
            <p>Gestão Financeira Empresarial</p>
        </header>

        <main class="login-card">
            <div class="card-header">
                <h2>Login de Gerente</h2>
            </div>

            <form action="processa_login.php" method="POST">
                <div class="input-group">
                    <label for="cpf">CPF</label>
                    <input 
                        type="text" 
                        id="cpf" 
                        name="cpf" 
                        placeholder="000 000 000-00" 
                        maxlength="11" 
                        inputmode="numeric"
                        pattern="\d{11}"
                        title="O CPF deve conter exatamente 11 números."
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        required
                    >
                </div>

                <div class="input-group">
                    <label for="senha">Senha</label>
                    <div class="password-wrapper">
                        <input type="password" id="senha" name="senha" placeholder="********" required>
                        <span class="toggle-password" onclick="toggleVisibility()">👁️</span>
                    </div>
                </div>

                <div class="form-options">
                    <label>
                        <input type="checkbox" name="remember"> Manter conectado
                    </label>
                    <a href="#" class="forgot-link">Esqueceu a senha?</a>
                </div>

                <button type="submit" class="btn-submit">Entrar como Gerente</button>
            </form>

            <div class="info-box">
                <p><strong>Acesso Gerencial:</strong> Esta área é exclusiva para gerentes e administradores. Se você é um colaborador, utilize o <a href="#">login padrão</a>.</p>
            </div>

            <div class="card-footer">
                <p>Não tem uma conta? <a href="../Cadastro.php/index.php">Cadastre-se</a></p>
            </div>
        </main>

        <footer class="page-footer">
            <p>© 2026 MetaCash. Todos os direitos reservados.</p>
        </footer>
    </div>

    <script>
        // Função simples para mostrar/esconder a senha
        function toggleVisibility() {
            const senhaInput = document.getElementById('senha');
            if (senhaInput.type === "password") {
                senhaInput.type = "text";
            } else {
                senhaInput.type = "password";
            }
        }
    </script>
</body>
</html>