<?php
// Captura o passo atual da recuperação pela URL (padrão é exibir o formulário de e-mail)
$step = filter_input(INPUT_GET, 'step', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'email';
// Mantém o e-mail na URL para exibir na tela de sucesso, se necessário
$email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL) ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - MetaCash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen bg-[url('../assets/img/fundo.png')] bg-cover bg-center bg-no-repeat flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="mb-6">
            <a href="login.php" class="inline-flex items-center gap-2 text-sm font-medium text-[#FFFFFF] hover:text-[#0F2440] transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Voltar ao login</span>
            </a>
        </div>

        <div class="bg-[#FDFEFB] rounded-2xl shadow-xl p-8 border border-[#B3E0F2]">
            
            <?php if ($step === 'email'): ?>
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-[#0F2440] to-[#204C73] rounded-2xl mb-4">
                        <i data-lucide="mail" class="w-8 h-8 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-[#0F2440] mb-2">Esqueceu sua senha?</h2>
                    <p class="text-[#204C73]">
                        Sem problemas! Digite seu e-mail e enviaremos instruções para redefinir sua senha.
                    </p>
                </div>

                <form action="processa-recuperacao.php" method="POST" class="space-y-5" id="recoveryForm">
                    <div>
                        <label for="email" class="block text-sm font-medium text-[#204C73] mb-2">
                            E-mail cadastrado
                        </label>
                        <div class="relative">
                            <i data-lucide="mail" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-[#204C73]"></i>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                class="w-full pl-11 pr-4 py-3 border border-[#0F2440] rounded-lg focus:ring-2 focus:ring-[#0F2440] focus:border-transparent outline-none transition-all"
                                placeholder="seu@email.com"
                                required
                            />
                        </div>
                    </div>

                    <button
                        type="submit"
                        id="btnSubmit"
                        class="w-full bg-gradient-to-r from-[#0F2440] to-[#204C73] text-white py-3 rounded-lg font-medium transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2"
                    >
                        <i data-lucide="send" class="w-5 h-5"></i>
                        <span>Enviar instruções</span>
                    </button>
                </form>

                <div class="mt-6 p-4 bg-[#B3E0F2]/20 rounded-lg border border-[#0F2440]">
                    <p class="text-xs text-[#0F2440]">
                        <strong>Importante:</strong> Verifique sua caixa de entrada e a pasta de spam. 
                        O e-mail pode levar alguns minutos para chegar.
                    </p>
                </div>

            <?php elseif ($step === 'sent'): ?>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-[#35C59A]/10 rounded-full mb-4">
                        <i data-lucide="check-circle" class="w-12 h-12 text-[#35C59A]"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-[#0F2440] mb-3">E-mail enviado!</h2>
                    <p class="text-[#204C73] mb-2">
                        Enviamos instruções para redefinir sua senha para:
                    </p>
                    <p class="text-lg font-medium text-[#24A6B6] mb-6">
                        <?php echo htmlspecialchars($email); ?>
                    </p>

                    <div class="space-y-3">
                        <div class="p-4 bg-gradient-to-r from-[#B3E0F2]/20 to-[#35C59A]/10 rounded-lg border border-[#0F2440] text-left">
                            <h3 class="font-medium text-[#0F2440] mb-2">Próximos passos:</h3>
                            <ol class="text-sm text-[#204C73] space-y-1 list-decimal list-inside">
                                <li>Verifique sua caixa de entrada</li>
                                <li>Clique no link de redefinição de senha</li>
                                <li>Crie uma nova senha segura</li>
                                <li>Faça login com suas novas credenciais</li>
                            </ol>
                        </div>

                        <a
                            href="login.php"
                            class="block w-full bg-gradient-to-r from-[#0F2440] to-[#204C73] text-white py-3 rounded-lg font-medium hover:from-[#204C73] hover:to-[#0F2440] transition-all shadow-lg hover:shadow-xl text-center"
                        >
                            Voltar ao login
                        </a>

                        <a
                            href="esqueceuSenha.php?step=email"
                            class="block w-full text-[#24A6B6] hover:text-[#0F2440] py-2 font-medium transition-colors text-center"
                        >
                            Não recebeu? Enviar novamente
                        </a>
                    </div>
                </div>

            <?php elseif ($step === 'error'): ?>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                        <i data-lucide="alert-circle" class="w-12 h-12 text-red-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-[#0F2440] mb-3">Ops! Algo deu errado</h2>
                    <p class="text-[#204C73] mb-6">
                        Não conseguimos encontrar uma conta associada a este e-mail. 
                        Verifique se digitou corretamente ou entre em contato com o suporte.
                    </p>

                    <div class="space-y-3">
                        <a
                            href="esqueceuSenha.php?step=email"
                            class="block w-full bg-gradient-to-r from-[#0F2440] to-[#204C73] text-white py-3 rounded-lg font-medium transition-all shadow-lg hover:shadow-xl text-center"
                        >
                            Tentar outro e-mail
                        </a>

                        <a
                            href="registro.php"
                            class="block w-full border-2 border-[#204C73] text-[#204C73] py-3 rounded-lg font-medium hover:bg-[#204C73] hover:text-white transition-all text-center"
                        >
                            Criar nova conta
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-[#FFFFFF]">
                Precisa de ajuda? Entre em contato com o suporte
                </a>
            </p>
        </div>

        <p class="text-center text-sm text-[#FFFFFF] mt-8">
            © 2026 MetaCash. Todos os direitos reservados.
        </p>
    </div>

    <script>
        lucide.createIcons();

        // Adiciona efeito visual de carregamento no botão ao submeter
        const form = document.getElementById('recoveryForm');
        if (form) {
            form.addEventListener('submit', function() {
                const btn = document.getElementById('btnSubmit');
                btn.disabled = true;
                btn.style.opacity = '0.7';
                btn.innerHTML = `
                    <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    <span>Enviando...</span>
                `;
            });
        }
    </script>
</body>
</html>