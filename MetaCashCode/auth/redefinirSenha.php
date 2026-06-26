<?php
// 1. Captura o token e o status enviados pelo seu backend via URL
$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

// Se não houver token, exibe a tela de link inválido/expirado
$linkInvalido = empty($token);

// 2. Mapeamento das condições de erro do seu código PHP
$mensagemErro = '';
if ($status === 'campos_vazios') {
    $mensagemErro = 'Por favor, preencha todos os campos.';
} elseif ($status === 'senhas_diferentes') {
    $mensagemErro = 'As senhas digitadas não coincidem.';
} elseif ($status === 'senha_fraca') {
    $mensagemErro = 'A senha não cumpre os requisitos de segurança mínimos.';
} elseif ($status === 'erro_banco') {
    $mensagemErro = 'Houve um erro no banco de dados. Tente novamente.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - MetaCash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen bg-[url('../assets/img/fundo.png')] bg-cover bg-center bg-no-repeat flex items-center justify-center p-4">

    <div id="popupSucesso" class="fixed top-5 left-1/2 -translate-x-1/2 bg-teal-500 text-white px-6 py-3 rounded-xl shadow-lg hidden z-[100] font-bold">
        Senha alterada com sucesso
    </div>

    <div class="w-full max-w-md">

        <?php if ($linkInvalido): ?>
            <div class="bg-[#FDFEFB] rounded-2xl shadow-xl p-8 border border-red-200 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                    <i data-lucide="alert-circle" class="w-12 h-12 text-red-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-[#0F2440] mb-3">Link Inválido</h2>
                <p class="text-[#204C73] mb-6">
                    Este link de redefinição de senha é inválido ou já expirou.
                </p>
                <a href="forgot-password.php" class="block w-full bg-gradient-to-r from-[#0F2440] to-[#204C73] text-white py-3 rounded-lg transition-all shadow-lg hover:shadow-xl font-medium text-center">
                    Solicitar Nova Recuperação
                </a>
            </div>

        <?php else: ?>
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-[#0F2440] to-[#204C73] rounded-2xl mb-4">
                    <i data-lucide="shield" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-[#FFFFFF] mb-2">Redefinir Senha</h1>
                <p class="text-[#FFFFFF]">Crie uma nova senha segura</p>
            </div>

            <div class="bg-[#FDFEFB] rounded-2xl shadow-xl p-8 border border-[#B3E0F2]">
                
                <?php if (!empty($mensagemErro)): ?>
                    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-red-600"><?php echo $mensagemErro; ?></p>
                    </div>
                <?php endif; ?>

                <form action="processarRedefinicao.php" method="POST" class="space-y-5" id="formRedefinir">
                    
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                    <div>
                        <label for="nova_senha" class="block text-sm font-medium text-[#204C73] mb-2">
                            Nova Senha
                        </label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-[#204C73]"></i>
                            <input
                                id="nova_senha"
                                name="nova_senha"
                                type="password"
                                class="w-full pl-11 pr-12 py-3 border border-[#0F2440] rounded-lg focus:ring-2 focus:ring-[#0F2440] focus:border-transparent outline-none transition-all"
                                placeholder="Digite sua nova senha"
                                required
                            />
                            <button type="button" onclick="togglePassword('nova_senha', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#204C73] hover:text-[#0F2440]">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="confirmar_senha" class="block text-sm font-medium text-[#204C73] mb-2">
                            Confirmar Nova Senha
                        </label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-[#204C73]"></i>
                            <input
                                id="confirmar_senha"
                                name="confirmar_senha"
                                type="password"
                                class="w-full pl-11 pr-12 py-3 border border-[#0F2440] rounded-lg focus:ring-2 focus:ring-[#0F2440] focus:border-transparent outline-none transition-all"
                                placeholder="Confirme sua nova senha"
                                required
                            />
                            <button type="button" onclick="togglePassword('confirmar_senha', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#204C73] hover:text-[#0F2440]">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </button>
                        </div>
                        
                        <div id="avisoMatch" class="mt-2 flex items-center gap-2 hidden">
                            <span id="matchIcon"></span>
                            <span id="matchText" class="text-xs"></span>
                        </div>
                    </div>

                    <div class="p-4 bg-[#B3E0F2]/20 rounded-lg border border-[#0F2440]">
                        <p class="text-xs font-medium text-[#0F2440] mb-2">Sua senha deve conter:</p>
                        <ul class="text-xs text-[#204C73] space-y-1.5">
                            <li class="flex items-center gap-2" id="req-length">
                                <div class="bullet w-1.5 h-1.5 rounded-full bg-gray-300 transition-colors"></div>
                                Pelo menos 8 caracteres
                            </li>
                            <li class="flex items-center gap-2" id="req-upper">
                                <div class="bullet w-1.5 h-1.5 rounded-full bg-gray-300 transition-colors"></div>
                                Uma letra maiúscula
                            </li>
                            <li class="flex items-center gap-2" id="req-number">
                                <div class="bullet w-1.5 h-1.5 rounded-full bg-gray-300 transition-colors"></div>
                                Um número
                            </li>
                            <li class="flex items-center gap-2" id="req-special">
                                <div class="bullet w-1.5 h-1.5 rounded-full bg-gray-300 transition-colors"></div>
                                Um caractere especial (ex: @, #, $, %)
                            </li>
                        </ul>
                    </div>

                    <button
                        type="submit"
                        id="btnSubmit"
                        class="w-full bg-gradient-to-r from-[#0F2440] to-[#204C73] text-white py-3 rounded-lg font-medium hover:from-[#204C73] hover:to-[#0F2440] transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2"
                    >
                        <i data-lucide="lock" class="w-5 h-5"></i>
                        <span>Redefinir Senha</span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="login.php" class="text-sm text-[#24A6B6] hover:text-[#0F2440] font-medium">
                        Voltar ao login
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <p class="text-center text-sm text-[#FFFFFF] mt-8">
            © 2026 MetaCash. Todos os direitos reservados.
        </p>
    </div>

    <script>
        lucide.createIcons();

        // Alternar visibilidade da senha
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        // Verifica se veio sucesso na URL
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('status') === 'sucesso') {
                const popup = document.getElementById('popupSucesso');
                popup.style.display = 'block';
                setTimeout(() => {
                    popup.style.display = 'none';
                }, 3000);
            }
        });

        const novaSenha = document.getElementById('nova_senha');
        const confirmarSenha = document.getElementById('confirmar_senha');
        const avisoMatch = document.getElementById('avisoMatch');
        const matchText = document.getElementById('matchText');
        const matchIcon = document.getElementById('matchIcon');
        const btnSubmit = document.getElementById('btnSubmit');

        function validarEstruturaERequisitos() {
            const valor = novaSenha.value;
            const regras = {
                'req-length': valor.length >= 8,
                'req-upper': /[A-Z]/.test(valor),
                'req-number': /[0-9]/.test(valor),
                'req-special': /[^a-zA-Z0-9]/.test(valor)
            };

            Object.keys(regras).forEach(id => {
                const item = document.getElementById(id);
                const bullet = item.querySelector('.bullet');
                if (regras[id]) {
                    bullet.className = 'bullet w-1.5 h-1.5 rounded-full bg-[#35C59A]';
                    item.classList.add('text-[#35C59A]');
                } else {
                    bullet.className = 'bullet w-1.5 h-1.5 rounded-full bg-gray-300';
                    item.classList.remove('text-[#35C59A]');
                }
            });
            verificarIgualdade();
        }

        function verificarIgualdade() {
            const senha = novaSenha.value;
            const confirmacao = confirmarSenha.value;

            if (!confirmacao) {
                avisoMatch.classList.add('hidden');
                return;
            }
            avisoMatch.classList.remove('hidden');

            if (senha === confirmacao) {
                matchText.textContent = 'As senhas coincidem';
                matchText.className = 'text-xs text-[#35C59A]';
                matchIcon.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4 text-[#35C59A]"></i>';
                btnSubmit.removeAttribute('disabled');
                btnSubmit.style.opacity = '1';
            } else {
                matchText.textContent = 'As senhas não coincidem';
                matchText.className = 'text-xs text-red-600';
                matchIcon.innerHTML = '<i data-lucide="alert-circle" class="w-4 h-4 text-red-600"></i>';
                btnSubmit.setAttribute('disabled', 'true');
                btnSubmit.style.opacity = '0.6';
            }
            lucide.createIcons();
        }

        if(novaSenha && confirmarSenha) {
            novaSenha.addEventListener('input', validarEstruturaERequisitos);
            confirmarSenha.addEventListener('input', verificarIgualdade);
        }
    </script>
</body>
</html>