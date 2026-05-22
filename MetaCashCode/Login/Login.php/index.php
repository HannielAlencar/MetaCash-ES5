<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Login</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="login.css/style.css">
    
    <style>
        .input-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        /* Ajuste para o campo de senha não ficar embaixo do ícone */
        .input-container input {
            width: 100%;
            padding-right: 40px; /* Espaço para o ícone não cobrir o texto */
        }
        /* Estilo para o ícone do olho */
        .toggle-password {
            position: absolute;
            right: 15px; /* Define a distância da borda direita */
            cursor: pointer;
            color: #666;
            z-index: 10; /* Garante que fique clicável por cima do campo */
            pointer-events: auto; /* Garante que o clique seja detectado */
        }
        .toggle-password:hover {
            color: #333;
        }
    </style>
</head>
<body>

    <div class="header-logo">
        <div class="icon">
            <img src="img/logo.png" alt="Logo MetaCash">
        </div> 
        <h1>MetaCash</h1>
        <p>Gestão Financeira Empresarial</p>
    </div>

    <div class="login-card">
        <h2>Entrar</h2>

        <?php if(isset($_GET['erro'])): ?>
            <div class="error-msg">E-mail ou senha incorretos!</div>
        <?php endif; ?>
        
        <form action="processar.php" method="POST">
            <div class="form-group">
                <label>E-mail</label>
                <div class="input-container">
                    <i class="fa-regular fa-envelope"></i>
                    <input type="email" name="email" placeholder="seu@email.com" 
                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                           title="Por favor, insira um e-mail válido" required>
                </div>
            </div>

            

            <div class="form-group">
                <label>Senha</label>
                <!-- Container com posição relativa forçada -->
                <div class="input-container" style="position: relative; display: flex; align-items: center;">
                    <i class="fa-solid fa-lock" style="position: absolute; left: 10px; z-index: 5;"></i>
                    
                    <input type="password" name="senha" id="password" placeholder="••••••••" required 
                           style="width: 100%; padding-left: 35px; padding-right: 40px; box-sizing: border-box;">
                    
                    <!-- Ícone do olho com posicionamento absoluto forçado à direita -->
                    <i class="fa-regular fa-eye toggle-password" id="togglePassword" 
                       style="position: absolute; right: 15px; cursor: pointer; color: #666; z-index: 10;"></i>
                </div>
            </div>

            <div class="options">
                <label>
                    <input type="checkbox" name="lembrar"> Lembrar-me
                </label>
                <a href="../Esquecer-senha.php/esqueceu-senha.php">Esqueceu senha?</a>
            </div>

            <button type="submit" class="btn-entrar">Entrar</button>
        </form>

        <p class="footer-text">Não tem uma conta? <a href="../Cadastro.php/index.php">Cadastre-se</a></p>
    </div>

    <p class="copyright">© 2026 MetaCash. Todos os direitos reservados.</p>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>