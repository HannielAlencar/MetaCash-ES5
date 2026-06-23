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

        // 2. Inserir o Usuário Admin
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

        // --- INÍCIO DA CORREÇÃO DE SESSÃO ---
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        session_start();
        
        // Busca os dados do usuário recém-criado
        $stmt_login = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt_login->execute([':email' => $email]);
        $usuario = $stmt_login->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // PADRONIZADO PARA usuario_id
            $_SESSION['usuario_id'] = $usuario['id_usuario']; 
            $_SESSION['nome'] = $usuario['nome_completo'];
            $_SESSION['nivel_permissao'] = $usuario['nivel_permissao'];
            $_SESSION['id_empresa'] = $usuario['id_empresa'];

            // Redirecionamento dinâmico baseado na permissão
            if ($usuario['nivel_permissao'] === 'Gerente') {
                header("Location: ../app/dashboardGerente.php");
            } else {
                header("Location: ../app/dashboardUsuario.php");
            }
            exit();
        } else {
            header("Location: ../auth/login.php");
            exit();
        }
        // --- FIM DA CORREÇÃO DE SESSÃO ---

    } catch (PDOException $e) {
        $pdo->rollBack(); 
        
        // Tratamento de erro para violação de chave única (23505 no PostgreSQL)
        if ($e->getCode() == '23505') {
            $msg = "Erro: Dados já cadastrados.";
            if (strpos($e->getMessage(), 'usuarios_email_key') !== false) {
                $msg = "Este e-mail já está sendo utilizado.";
            } elseif (strpos($e->getMessage(), 'usuarios_cpf_key') !== false) {
                $msg = "Este CPF já possui uma conta.";
            }
            header("Location: cadastro.php?erro=" . urlencode($msg));
            exit();
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
    <title>Criar Conta - MetaCash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/cadastro.css">
</head>
<body class="h-screen overflow-hidden relative text-slate-800 flex items-center justify-center">
    <div id="successPopup" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 opacity-0 transition-opacity duration-500">
        <div class="bg-white p-8 rounded-2xl shadow-2xl text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-2xl text-green-600"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Conta criada com sucesso</h3>
        </div>
    </div>

    <?php if (isset($_GET['erro'])): ?>
        <div class="fixed top-4 z-50 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
            <?php echo htmlspecialchars($_GET['erro']); ?>
        </div>
    <?php endif; ?>

    <div class="noise-overlay"></div>

    <a href="../auth/login.php" class="fixed top-4 left-6 z-20 inline-flex items-center gap-2 text-white font-medium hover:text-slate-200 transition-colors">
        <i class="fas fa-arrow-left text-sm"></i> Voltar
    </a>

    <div class="relative z-10 w-full max-w-xl px-4">
        <div class="flex flex-col items-center mb-4">
            <div class="mb-2">
                <img src="../assets/img/logo.png" alt="Logo MetaCash" class="w-14 h-14 object-contain">
            </div>
            <h1 class="text-white text-xl font-bold tracking-tight">MetaCash</h1>
            <p class="text-slate-300 text-xs font-medium">Cadastre sua empresa</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-6">
            <h2 class="text-lg font-bold mb-4">Criar Conta</h2>
            
            <form id="signupForm" action="cadastro.php" method="POST" class="space-y-2.5">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Nome da Empresa <span class="text-red-500">*</span></label>
                        <input type="text" id="empresa" name="nome_empresa" placeholder="Empresa LTDA" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Nome do Responsável <span class="text-red-500">*</span></label>
                        <input type="text" id="responsavel" name="nome_completo" placeholder="Joao Silva" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">E-mail <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" placeholder="email@gmail.com" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Matricula <span class="text-red-500">*</span></label>
                        <input type="text" id="matricula" name="matricula" placeholder="M29L0" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">CNPJ <span class="text-red-500">*</span></label>
                        <input type="text" id="cnpj" name="cnpj" maxlength="18" placeholder="00.000.000/0000-00" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">CPF <span class="text-red-500">*</span></label>
                        <input type="text" id="cpf" name="cpf" maxlength="14" placeholder="000.000.000-00" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Senha <span class="text-red-500">*</span></label>
                        <input type="password" id="senha" name="senha" placeholder="********" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Confirmar Senha <span class="text-red-500">*</span></label>
                        <input type="password" id="confirmaSenha" name="confirmar_senha" placeholder="********" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-100 rounded-lg p-2 mt-1">
                    <p class="text-[10px] font-bold text-slate-700 mb-1">Requisitos de Senha:</p>
                    <div class="grid grid-cols-2 gap-1 text-[9px] text-slate-500">
                        <p id="req-length"><i class="fas fa-times text-red-400 mr-1"></i> 8+ caracteres</p>
                        <p id="req-upper"><i class="fas fa-times text-red-400 mr-1"></i> Maiúscula</p>
                        <p id="req-lower"><i class="fas fa-times text-red-400 mr-1"></i> Minúscula</p>
                        <p id="req-num"><i class="fas fa-times text-red-400 mr-1"></i> Número</p>
                        <p id="req-special"><i class="fas fa-times text-red-400 mr-1"></i> Caractere especial</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 py-1">
                    <input type="checkbox" id="terms" name="terms" class="rounded border-slate-300 text-blue-900" required>
                    <label for="terms" class="text-[11px] text-slate-600">
                        Eu aceito os 
                        <a href="../app/termos.php" target="_blank" class="text-blue-900 font-bold underline">Termos</a> 
                        e a 
                        <a href="../app/politica.php" target="_blank" class="text-blue-900 font-bold underline">Política</a>
                    </label>
                </div>

                <button type="submit" class="w-full bg-[#0a1b2e] hover:bg-[#1a2f45] text-white font-bold py-2 rounded-lg transition-all shadow-md text-sm">
                    Criar Conta
                </button>
            </form>

            <p class="text-center text-[11px] text-slate-500 mt-3">Já tem uma conta? <a href="../auth/login.php" class="text-blue-900 font-bold hover:underline">Fazer login</a></p>
        </div>

        <p class="text-center text-[10px] text-slate-400 mt-3">© <?php echo date('Y'); ?> MetaCash. Todos os direitos reservados.</p>
    </div>

<script src="../assets/js/cadastro.js"></script>
</body>
</html>