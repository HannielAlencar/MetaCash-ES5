<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';

$profile_error = '';
$profile_success = '';
$password_error = '';
$password_success = '';

$id_usuario = $_SESSION['id_usuario'] ?? null;
$nome_usuario = $_SESSION['nome_usuario'] ?? 'Usuario';
$email_usuario = $_SESSION['email_usuario'] ?? 'usuario@exemplo.com';
$matricula_usuario = $_SESSION['matricula'] ?? '000000';
$cpf_usuario = $_SESSION['cpf_usuario'] ?? '000.000.000-00';
$papel_usuario = $_SESSION['nivel_permissao'] ?? 'Membro';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id_usuario) {
    $form_type = $_POST['form_type'] ?? '';

    if ($form_type === 'profile') {
        $email = trim($_POST['email'] ?? '');

        if ($email === '') {
            $profile_error = 'O e-mail é obrigatório.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $profile_error = 'Formato de e-mail inválido.';
        } else {
            try {
                $stmt = $pdo->prepare('UPDATE usuarios SET email = :email WHERE id_usuario = :id');
                $stmt->execute([':email' => $email, ':id' => $id_usuario]);
                $_SESSION['email_usuario'] = $email;
                $email_usuario = $email;
                $profile_success = 'Dados do perfil atualizados com sucesso.';
            } catch (PDOException $e) {
                if (in_array($e->getCode(), ['23000', '23505'])) {
                    $profile_error = 'Esse e-mail já está em uso.';
                } else {
                    $profile_error = 'Erro ao atualizar perfil: ' . $e->getMessage();
                }
            }
        }
    } elseif ($form_type === 'password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($current_password === '' || $new_password === '' || $confirm_password === '') {
            $password_error = 'Preencha todos os campos de senha.';
        } elseif ($new_password !== $confirm_password) {
            $password_error = 'A nova senha e a confirmação não coincidem.';
        } elseif (strlen($new_password) < 8) {
            $password_error = 'A nova senha precisa ter pelo menos 8 caracteres.';
        } else {
                try {
                    $stmt = $pdo->prepare('SELECT senha FROM usuarios WHERE id_usuario = :id LIMIT 1');
                    $stmt->execute([':id' => $id_usuario]);
                    $hash = $stmt->fetchColumn();

                    if (!password_verify($current_password, $hash)) {
                        $password_error = 'Senha atual incorreta.';
                    } elseif (password_verify($new_password, $hash)) {
                        $password_error = 'A nova senha precisa ser diferente da senha atual.';
                    } else {
                        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare('UPDATE usuarios SET senha = :senha WHERE id_usuario = :id');
                        $stmt->execute([':senha' => $new_hash, ':id' => $id_usuario]);
                        $password_success = 'Senha atualizada com sucesso.';
                    }
                } catch (PDOException $e) {
                    $password_error = 'Erro ao atualizar senha: ' . $e->getMessage();
                }
        }
    }
}

if ($id_usuario) {
    try {
        $stmt = $pdo->prepare('SELECT nome_completo, email, matricula, cpf, nivel_permissao FROM usuarios WHERE id_usuario = :id LIMIT 1');
        $stmt->execute([':id' => $id_usuario]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            $nome_usuario = $usuario['nome_completo'] ?: $nome_usuario;
            $email_usuario = $usuario['email'] ?: $email_usuario;
            $matricula_usuario = $usuario['matricula'] ?: $matricula_usuario;
            $cpf_usuario = $usuario['cpf'] ?: $cpf_usuario;
            $papel_usuario = $usuario['nivel_permissao'] ?: $papel_usuario;
        }
    } catch (PDOException $e) {
        error_log('Erro ao carregar perfil: ' . $e->getMessage());
    }
}

if (function_exists('mb_substr')) {
    $iniciais = mb_strtoupper(mb_substr($nome_usuario, 0, 1));
} else {
    $iniciais = strtoupper(substr($nome_usuario, 0, 1));
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Meu Perfil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc]">

    <div class="flex min-h-screen">
        <?php require_once '../includes/sidebar.php'; ?>

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="flex-1 p-10 ml-64 w-full">
        <!-- Cabeçalho da Página -->
        <header class="mb-8">
            <a href="javascript:history.back()" class="text-sm text-slate-500 hover:text-teal-600 flex items-center gap-2 mb-4 transition">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <h1 class="text-3xl font-extrabold text-[#0f172a]">Meu Perfil</h1>
            <p class="text-slate-500 mt-1">Gerencie suas informações pessoais e segurança</p>
        </header>

        <div class="max-w-5xl space-y-8">
            
            <!-- CARD: INFORMAÇÕES PESSOAIS -->
            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <h2 class="font-bold text-slate-800">Informações Pessoais</h2>
                </div>
                
                <div class="p-8">
                    <!-- Header do Perfil (Avatar) -->
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-20 h-20 bg-[#2dd4bf] rounded-full flex items-center justify-center text-[#0f172a] text-3xl font-bold">
                            <?= htmlspecialchars($iniciais, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800"><?= htmlspecialchars($nome_usuario, ENT_QUOTES, 'UTF-8') ?></h3>
                            <span class="inline-block bg-blue-100 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase mb-1">
                                <i class="fas fa-shield-alt mr-1"></i> <?= htmlspecialchars($papel_usuario, ENT_QUOTES, 'UTF-8') ?>
                            </span>
                            <p class="text-sm text-slate-400"><?= htmlspecialchars($email_usuario, ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>

                    <form class="space-y-6" method="POST" action="perfil.php">
                        <input type="hidden" name="form_type" value="profile">
                        <?php if ($profile_error): ?>
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg"><?= htmlspecialchars($profile_error, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php elseif ($profile_success): ?>
                            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg"><?= htmlspecialchars($profile_success, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Nome -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nome Completo</label>
                                <div class="relative">
                                    <i class="far fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" value="<?= htmlspecialchars($nome_usuario, ENT_QUOTES, 'UTF-8') ?>" disabled class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 italic">Este campo não pode ser alterado</p>
                            </div>

                            <!-- Matrícula -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Matrícula</label>
                                <div class="relative">
                                    <i class="far fa-id-badge absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" value="<?= htmlspecialchars($matricula_usuario, ENT_QUOTES, 'UTF-8') ?>" disabled class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 italic">Este campo não pode ser alterado</p>
                            </div>

                            <!-- CPF -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">CPF</label>
                                <div class="relative">
                                    <i class="far fa-address-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" value="<?= htmlspecialchars($cpf_usuario, ENT_QUOTES, 'UTF-8') ?>" disabled class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 italic">Este campo não pode ser alterado</p>
                            </div>

                            <!-- Papel -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Papel</label>
                                <div class="w-full px-4 py-3 rounded-xl bg-sky-100 border border-sky-200 text-sky-700 flex items-center gap-3">
                                    <i class="fas fa-user-tag text-sky-500"></i>
                                    <span class="font-bold text-sm"><?= htmlspecialchars($papel_usuario, ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1">Definido pelo gerente da empresa</p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email</label>
                                <div class="relative">
                                    <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="email" name="email" value="<?= htmlspecialchars($email_usuario, ENT_QUOTES, 'UTF-8') ?>" class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 outline-none transition">
                                </div>
                                <p class="text-[10px] text-teal-600 mt-1 italic"><i class="fas fa-check"></i> Este campo pode ser alterado</p>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="bg-gradient-to-r from-slate-800 to-teal-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:opacity-90 transition flex items-center gap-2">
                                <i class="fas fa-save"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- CARD: SEGURANÇA -->
            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-lock text-sm"></i>
                    </div>
                    <h2 class="font-bold text-slate-800">Segurança</h2>
                </div>

                <div class="p-8">
                    <form class="space-y-6" method="POST" action="perfil.php">
                        <input type="hidden" name="form_type" value="password">
                        <?php if ($password_error): ?>
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg"><?= htmlspecialchars($password_error, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php elseif ($password_success): ?>
                            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg"><?= htmlspecialchars($password_success, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Senha Atual <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="fas fa-unlock-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input id="current_password" type="password" name="current_password" placeholder="Digite sua senha atual" class="w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 outline-none transition">
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nova Senha <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="fas fa-unlock-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input id="new_password" type="password" name="new_password" placeholder="Digite sua nova senha" class="w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 outline-none transition">
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Confirmar Nova Senha <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="fas fa-unlock-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input id="confirm_password" type="password" name="confirm_password" placeholder="Confirme sua nova senha" class="w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 outline-none transition">
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row justify-between items-end gap-6 pt-4">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 w-full md:w-fit">
                                <h4 class="text-xs font-bold text-slate-600 mb-3">Requisitos de Senha:</h4>
                                <div class="grid grid-cols-2 gap-x-8 gap-y-2">
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i class="fas fa-times text-red-400"></i> Mínimo de 8 caracteres</div>
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i class="fas fa-times text-red-400"></i> Letra maiúscula (A-Z)</div>
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i class="fas fa-times text-red-400"></i> Letra minúscula (a-z)</div>
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i class="fas fa-times text-red-400"></i> Número (0-9)</div>
                                    <div class="text-[10px] text-slate-500 flex items-center gap-2"><i class="fas fa-times text-red-400"></i> Caractere especial (!@#$...)</div>
                                </div>
                            </div>
                            <!-- BOTÃO ATUALIZADO -->
                            <button type="submit" class="bg-slate-100 text-slate-600 px-6 py-2.5 rounded-lg text-sm hover:bg-red-50 hover:text-red-600 transition duration-300 flex items-center gap-2 border border-slate-200 hover:border-red-200 shadow-sm">
                                <i class="fas fa-key text-xs"></i> Alterar Senha
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        </main>
    </div>

</body>
</html>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const pwdForm = document.querySelector('form[input][name]') || document.querySelector('form[action="perfil.php"][method="POST"][name]')
});
// Password form validation
(function(){
    const form = document.querySelector('form input[name="form_type"][value="password"]') ? document.querySelector('form input[name="form_type"][value="password"]').closest('form') : document.querySelector('form[action="perfil.php"][method="POST"]');
    if (!form) return;

    // create client error container
    const errDiv = document.createElement('div');
    errDiv.id = 'password-client-error';
    errDiv.style.display = 'none';
    errDiv.className = 'mb-4 px-4 py-3 rounded-lg text-red-700 bg-red-50 border border-red-200';
    form.insertBefore(errDiv, form.firstChild);

    form.addEventListener('submit', function(e){
        const current = document.getElementById('current_password')?.value || '';
        const neu = document.getElementById('new_password')?.value || '';
        const conf = document.getElementById('confirm_password')?.value || '';

        if (neu !== '' && current !== '' && neu === current) {
            e.preventDefault();
            errDiv.textContent = 'A nova senha precisa ser diferente da senha atual.';
            errDiv.style.display = 'block';
            return;
        }

        if (neu !== '' && conf !== '' && neu !== conf) {
            e.preventDefault();
            errDiv.textContent = 'A nova senha e a confirmação não coincidem.';
            errDiv.style.display = 'block';
            return;
        }

        // hide if passes
        errDiv.style.display = 'none';
    });
})();
</script>