<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Login</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="login.css/style.css">
    
    <style>
        .input-container {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }
        .input-container input {
            width: 100%;
            padding: 12px 15px 12px 40px; 
            box-sizing: border-box;
        }
        .input-container i.left-icon {
            position: absolute;
            left: 15px;
            color: #666;
        }
        .error-msg {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Pop-up de Sucesso -->
    <div id="successPopup" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 opacity-0 transition-opacity duration-500">
        <div class="bg-white p-8 rounded-2xl shadow-2xl text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-2xl text-green-600"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Login realizado com sucesso</h3>
        </div>
    </div>

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
            <div class="error-msg" id="msgErro">
                <?php 
                    if($_GET['erro'] == 'senha') echo "Senha incorreta!";
                    elseif($_GET['erro'] == 'email') echo "E-mail não cadastrado!";
                    else echo "E-mail ou senha incorretos!";
                ?>
            </div>
        <?php endif; ?>
        
        <form id="loginForm">
            <div class="form-group">
                <label>E-mail</label>
                <div class="input-container">
                    <i class="fa-regular fa-envelope left-icon"></i>
                    <input type="email" name="email" id="email" placeholder="seu@email.com" required>
                </div>
            </div>

            <div class="form-group">
                <label>Senha</label>
                <div class="input-container">
                    <i class="fa-solid fa-lock left-icon"></i>
                    <input type="password" name="senha" id="password" placeholder="••••••••" required>
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
        const form = document.getElementById('loginForm');
        const successPopup = document.getElementById('successPopup');

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;

            // Validação simples
            if (email.trim() === "" || password.trim() === "") {
                return alert("Por favor, preencha todos os campos.");
            }
            if (!emailPattern.test(email)) {
                return alert("Por favor, insira um formato de e-mail válido.");
            }

            // Verificação com LocalStorage
            const storedEmail = localStorage.getItem('userEmail');
            const storedPassword = localStorage.getItem('userPassword');

            if (email === storedEmail && password === storedPassword) {
                // Sucesso
                successPopup.classList.remove('hidden');
                setTimeout(() => successPopup.classList.remove('opacity-0'), 10);
                
                setTimeout(() => {
                    successPopup.classList.add('opacity-0');
                    setTimeout(() => {
                        window.location.href = "/MetaCashCode/Usuario/Dashboard/index.php";
                    }, 500);
                }, 3000);
            } else {
                alert("E-mail ou senha incorretos!");
            }
        });
    </script>
</body>
</html>