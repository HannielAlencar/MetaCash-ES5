<?php session_start(); 
require_once __DIR__ . '/../config.php';
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        // Busca o usuário pelo e-mail
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();

        // Verifica se o usuário existe e se a senha está correta
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            // Salva os dados na Sessão para usar em todas as outras telas
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['id_empresa'] = $usuario['id_empresa'];
            $_SESSION['nome_usuario'] = $usuario['nome_completo'];
            $_SESSION['matricula'] = $usuario['matricula'];

            header("Location: ../app/dashboardUsuario.php");
            exit();
            
        } else {
            $erro = "E-mail ou senha incorretos.";
        }
    } catch (PDOException $e) {
        $erro = "Erro no sistema: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Login</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>

    <div class="header-logo">
        <div class="icon">
            <img src="../assets/img/logo.png" alt="Logo MetaCash">
        </div> 
        <h1>MetaCash</h1>
        <p>Gestão Financeira Empresarial</p>
    </div>

    <div class="login-card">
        <h2>Entrar</h2>

        <?php if(isset($_GET['erro'])): ?>
            <div class="error-msg">E-mail ou senha incorretos!</div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="form-group">
                <label>E-mail</label>
                <div class="input-container">
                    <i class="fa-regular fa-envelope"></i>
                    <input type="email" name="email" placeholder="seu@email.com" required>
                </div>
            </div>

            <div class="form-group">
                <label>Senha</label>
                <div class="input-container">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="senha" placeholder="••••••••" required>
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

        <p class="footer-text">Não tem uma conta? <a href="../Cadastro.php/index.php">Cadastre-se</a>
    </div>

    <p class="copyright">© 2026 MetaCash. Todos os direitos reservados.</p>

</body>
</html>
