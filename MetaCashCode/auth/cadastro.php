<?php
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se as senhas batem antes de fazer qualquer coisa
    if ($_POST['senha'] !== $_POST['confirmar_senha']) {
        header("Location: cadastro.php?erro=As senhas não coincidem!");
        exit();
    }

    // Dados da Empresa
    $nome_empresa = $_POST['nome_empresa'];
    $cnpj = $_POST['cnpj'];

    // Dados do Usuário Admin
    $matricula = $_POST['matricula'] ?? null; 
    $cpf = $_POST['cpf'];
    $nome_utilizador = $_POST['nome_completo']; 
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    try {
        $pdo->beginTransaction(); 

        // 1. Inserir a Empresa
        $sql_empresa = "INSERT INTO empresas (nome_empresa, cnpj) VALUES (:nome, :cnpj)";
        $stmt_empresa = $pdo->prepare($sql_empresa);
        $stmt_empresa->execute([':nome' => $nome_empresa, ':cnpj' => $cnpj]);
        
        $id_empresa = $pdo->lastInsertId();

        // 2. Inserir o Usuário Admin (AGORA COM MATRÍCULA E CPF)
        $sql_usuario = "INSERT INTO usuarios (id_empresa, matricula, nome_completo, cpf, email, senha, nivel_permissao) 
                        VALUES (:id_empresa, :matricula, :nome, :cpf, :email, :senha, 'Gerente')";
        $stmt_usuario = $pdo->prepare($sql_usuario);
        $stmt_usuario->execute([
            ':id_empresa' => $id_empresa,
            ':matricula'  => $matricula,
            ':nome'       => $nome_utilizador,
            ':cpf'        => $cpf,
            ':email'      => $email,
            ':senha'      => $senha
        ]);

        $pdo->commit(); 
        header("Location: ../app/dashboardUsuario.php");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack(); 
        
        // Tratamento de erro para CPF ou CNPJ duplicado
        if ($e->getCode() == 23000) {
            die("Erro: Já existe um cadastro com esse CPF, CNPJ ou E-mail.");
        } else {
            die("Erro no sistema: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Criar Conta</title>
    <link rel="stylesheet" href="../assets/css/cadastro.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
    <header class="logo-header">
        <img src="../assets/img/logo.png" alt="MetaCash Logo" class="icon-main">
        <h1>MetaCash</h1>
        <p>Cadastre sua empresa</p>
    </header>

    <div class="card">
        <h2>Criar Conta</h2>
        
        <?php if(isset($_GET['erro'])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_GET['erro']); ?></div>
        <?php endif; ?>
        <?php if(isset($_GET['sucesso'])): ?>
            <div class="alert success">Cadastro realizado com sucesso!</div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="grid">
                <div class="input-group">
                    <label>Nome da Empresa <span>*</span></label>
                    <div class="input-with-icon">
                        <i>🏢</i>
                        <input type="text" name="nome_empresa" placeholder="Empresa LTDA" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>Nome do Responsável <span>*</span></label>
                    <div class="input-with-icon">
                        <i>👤</i>
                        <input type="text" name="nome_completo" placeholder="João Silva" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>E-mail <span>*</span></label>
                    <div class="input-with-icon">
                        <i>✉️</i>
                        <input type="email" name="email" placeholder="contato@empresa.com" required>
                    </div>
                </div>
                <div class="input-group">
                    <label> Matrícula <span>*</span></label>
                    <div class="input-with-icon">
                        <i>🏷️</i>
                        <input type="text" name="matricula" placeholder="0021840" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>CNPJ <span>*</span></label>
                    <div class="input-with-icon">
                        <i>📄</i>
                        <input type="text" name="cnpj" placeholder="00.000.000/0000-00" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>CPF <span>*</span></label>
                    <div class="input-with-icon">
                        <i>👤</i>
                        <input type="text" name="cpf" placeholder="000.000.000-00" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>Senha <span>*</span></label>
                    <div class="input-with-icon">
                        <i>🔒</i>
                        <input type="password" name="senha" placeholder="••••••••" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>Confirmar Senha <span>*</span></label>
                    <div class="input-with-icon">
                        <i>🔒</i>
                        <input type="password" name="confirmar_senha" placeholder="••••••••" required>
                    </div>
                </div>
            </div>

            <div class="requirements-container">
                <p>Requisitos de Senha:</p>
                <div class="requirements-grid">
                    <span>✕ Mínimo de 8 caracteres</span>
                    <span>✕ Letra maiúscula (A-Z)</span>
                    <span>✕ Letra minúscula (a-z)</span>
                    <span>✕ Número (0-9)</span>
                    <span>✕ Caractere especial (!@#$...)</span>
                </div>
            </div>

            <div class="terms">
                <input type="checkbox" id="termos" name="termos" required>
                <label for="termos">Eu aceito os <a href="#">Termos de Uso</a> e a <a href="#">Política de Privacidade</a></label>
            </div>

            <button type="submit" class="btn-submit">Criar Conta</button>
        </form>

        <p class="login-link">Já tem uma conta? <a href="../Login.php/index.php">Fazer login</a></p>
    </div>
    
    <footer class="footer-copyright">
        © 2026 MetaCash. Todos os direitos reservados.
    </footer>
</div>

</body>
</html>
