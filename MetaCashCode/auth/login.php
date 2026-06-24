<?php
ob_start(); // Previne erros de redirecionamento causados por espaços em branco no config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $erro = 'Preencha e-mail e senha.';
    } else {
        try {
            // Busca o usuário pelo e-mail
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            // Verifica se o usuário existe e se a senha está correta
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Salva os dados na Sessao para usar em todas as outras telas
                $_SESSION['usuario_id'] = $usuario['id_usuario']; // CHAVE PADRONIZADA PARA O DASHBOARD LIBERAR O ACESSO
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['id_empresa'] = $usuario['id_empresa'];
                $_SESSION['nome'] = $usuario['nome_completo'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['matricula'] = $usuario['matricula'];
                $_SESSION['cpf_usuario'] = $usuario['cpf'];
                $_SESSION['nivel_permissao'] = $usuario['nivel_permissao'];

                // Redirecionamento condicional baseado no nível de permissão
                if ($usuario['nivel_permissao'] === 'Gerente' || $usuario['nivel_permissao'] === 'Admin') {
                    header("Location: ../app/dashboardGerente.php");
                } else {
                    header("Location: ../app/dashboardUsuario.php");
                }
                exit();
            }

            $erro = 'E-mail ou senha incorretos.';
        } catch (PDOException $e) {
            $erro = 'Erro no sistema: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Login</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    
</head>
<body>

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
            <img src="../assets/img/logo.png" alt="Logo MetaCash">
        </div> 
        <h1>MetaCash</h1>
        <p>Gestão Financeira Empresarial</p>
    </div>

    <div class="login-card">
        <h2>Entrar</h2>

        <?php if ($erro): ?>
            <div class="error-msg" id="msgErro">
                <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
        
        <form id="loginForm" method="POST" action="login.php">
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
                    <i class="fa-regular fa-eye right-icon toggle-password" data-target="password"></i>
                </div>
            </div>

            <div class="options">
                <label>
                    <input type="checkbox" name="lembrar"> Lembrar-me
                </label>
                <a href="../auth/esqueceuSenha.php">Esqueceu senha?</a>
            </div>

            <button type="submit" class="btn-entrar">Entrar</button>
        </form>

        <p class="footer-text">Não tem uma conta? <a href="../auth/cadastro.php">Cadastre-se</a></p>
    </div>

    <p class="copyright">© 2026 MetaCash. Todos os direitos reservados.</p>

<script src="../assets/js/login.js"></script>
</body>
</html>