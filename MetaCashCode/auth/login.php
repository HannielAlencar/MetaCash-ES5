<?php
ob_start(); // Previne erros de redirecionamento causados por espaços em branco no config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';

$erro = null;
$aviso = null;
$sucesso = false;
$urlRedirecionamento = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Verifica se os campos estão vazios
    if ($email === '' || $senha === '') {
        $erro = 'Preencha todos os campos obrigatórios.';
    } 
    // NOVA VALIDAÇÃO: Verifica se o formato do e-mail é válido no PHP
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Por favor, insira um e-mail válido.';
    } 
    else {
        try {
            // Busca o usuário pelo e-mail
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            // Verifica se o usuário existe e se a senha está correta
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                
                // VERIFICAÇÃO DE CONTA DESATIVADA: 
                $statusConta = isset($usuario['status']) ? strtolower((string)$usuario['status']) : '';
                
                if ($statusConta === 'inativo' || $statusConta === 'desativado' || $statusConta === '0') {
                    $aviso = 'Sua conta foi desativada ou excluída pelo administrador do sistema.';
                } else {
                    // Salva os dados na Sessao
                    $_SESSION['usuario_id'] = $usuario['id_usuario'];
                    $_SESSION['id_usuario'] = $usuario['id_usuario'];
                    $_SESSION['id_empresa'] = $usuario['id_empresa'];
                    $_SESSION['nome'] = $usuario['nome_completo'];
                    $_SESSION['email'] = $usuario['email'];
                    $_SESSION['matricula'] = $usuario['matricula'];
                    $_SESSION['cpf_usuario'] = $usuario['cpf'];
                    $_SESSION['nivel_permissao'] = $usuario['nivel_permissao'];

                    // Ativa o popup de sucesso e define a rota
                    $sucesso = true;
                    if ($usuario['nivel_permissao'] === 'Gerente') {
                        $urlRedirecionamento = "../app/dashboardGerente.php";
                    } else if ($usuario['nivel_permissao'] === 'Admin') {
                        $urlRedirecionamento = "../app/empresasADMIN.php";
                    } else {
                        $urlRedirecionamento = "../app/dashboardUsuario.php";
                    }
                }
            } else {
                $erro = 'E-mail ou senha incorretos.';
            }
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

    <div id="popupSucesso" class="fixed top-5 left-1/2 -translate-x-1/2 bg-teal-500 text-white px-6 py-3 rounded-xl shadow-lg hidden z-[100] font-bold">
     Senha alterada com sucesso!
    </div>

    <div id="successPopup" class="<?= $sucesso ? 'fixed opacity-100' : 'hidden opacity-0' ?> inset-0 z-50 flex items-center justify-center bg-black/50 transition-opacity duration-500">
        <div class="bg-white p-8 rounded-2xl shadow-2xl text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-2xl text-green-600"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Login realizado com sucesso</h3>
            <p class="text-sm text-slate-500 mt-2">Redirecionando...</p>
        </div>
    </div>

    <div id="warningPopup" class="<?= $aviso ? 'fixed opacity-100' : 'hidden opacity-0' ?> inset-0 z-50 flex items-center justify-center bg-black/50 transition-opacity duration-500">
        <div class="bg-white p-8 rounded-2xl shadow-2xl text-center max-w-sm mx-4">
            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-2xl text-orange-600"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Acesso Negado</h3>
            <p class="text-sm text-slate-600"><?= htmlspecialchars($aviso ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            <button onclick="document.getElementById('warningPopup').classList.add('hidden'); document.getElementById('warningPopup').classList.remove('fixed');" class="mt-6 px-4 py-2 bg-orange-500 hover:bg-orange-600 transition-colors text-white font-semibold rounded-lg w-full">Entendi</button>
        </div>
    </div>

    <div id="errorPopup" class="<?= $erro ? 'fixed opacity-100' : 'hidden opacity-0' ?> inset-0 z-50 flex items-center justify-center bg-black/50 transition-opacity duration-500">
        <div class="bg-white p-8 rounded-2xl shadow-2xl text-center max-w-sm mx-4">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-times-circle text-2xl text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Atenção</h3>
            <p id="errorMsgText" class="text-sm text-slate-600"><?= htmlspecialchars($erro ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            <button onclick="fecharPopupErro()" class="mt-6 px-4 py-2 bg-red-500 hover:bg-red-600 transition-colors text-white font-semibold rounded-lg w-full">Fechar</button>
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
        
        <form id="loginForm" method="POST" action="login.php" novalidate>
            <div class="form-group">
                <label>E-mail <span class="text-red-500">*</span></label>
                <div class="input-container">
                    <i class="fa-regular fa-envelope left-icon"></i>
                    <input type="email" name="email" id="email" placeholder="seu@email.com" required>
                </div>
            </div>

            <div class="form-group">
                <label>Senha <span class="text-red-500">*</span></label>
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

<script>
    // Função para fechar o popup de erro
    function fecharPopupErro() {
        const popup = document.getElementById('errorPopup');
        popup.classList.add('hidden', 'opacity-0');
        popup.classList.remove('fixed', 'opacity-100');
    }

    // Intercepta o envio do formulário para validar os campos antes do PHP
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();
        const senha = document.getElementById('password').value.trim();
        
        // Expressão regular para validar o formato do e-mail
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        let mensagemErro = '';

        if (email === '' || senha === '') {
            mensagemErro = 'Preencha todos os campos obrigatórios.';
        } else if (!emailRegex.test(email)) {
            mensagemErro = 'Por favor, insira um e-mail válido (ex: nome@empresa.com).';
        }

        // Se houver algum erro, impede o envio e mostra o popup
        if (mensagemErro !== '') {
            e.preventDefault(); 
            document.getElementById('errorMsgText').innerText = mensagemErro;
            
            const popup = document.getElementById('errorPopup');
            popup.classList.remove('hidden', 'opacity-0');
            popup.classList.add('fixed', 'opacity-100');
        }
    });

    <?php if ($sucesso): ?>
        setTimeout(function() {
            window.location.href = "<?= $urlRedirecionamento ?>";
        }, 1500);
    <?php endif; ?>
</script>

<script>
    // Verifica se veio o status de senha atualizada na URL do login
    window.addEventListener('load', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('status') === 'senha_atualizada') {
            const popup = document.getElementById('popupSucesso');
            popup.style.display = 'block';
            
            // Esconde após 4 segundos
            setTimeout(() => {
                popup.style.display = 'none';
            }, 4000);
        }
    });
</script>

</body>
</html>