<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - MetaCash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background: radial-gradient(circle at 70% 30%, #2b6a7a 0%, #152c3f 50%, #0a111a 100%);
            background-attachment: fixed;
        }
        .noise-overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            pointer-events: none; z-index: 0; opacity: 0.1;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="h-screen overflow-hidden relative text-slate-800 flex items-center justify-center">
    <div class="noise-overlay"></div>

    <a href="/MetaCashCode/Home/HomePB/index.php" class="fixed top-4 left-6 z-20 inline-flex items-center gap-2 text-white font-medium hover:text-slate-200 transition-colors">
        <i class="fas fa-arrow-left text-sm"></i> Voltar
    </a>

    <div class="relative z-10 w-full max-w-xl px-4">
        <div class="flex flex-col items-center mb-4">
            <div class="mb-2">
                <img src="/MetaCashCode/Usuario/Dashboard/img/LogoAzulEscuro.png" alt="Logo MetaCash" class="w-14 h-14 object-contain">
            </div>
            <h1 class="text-white text-xl font-bold tracking-tight">MetaCash</h1>
            <p class="text-slate-300 text-xs font-medium">Cadastre sua empresa</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-6">
            <h2 class="text-lg font-bold mb-4">Criar Conta</h2>
            
            <form id="signupForm" action="#" method="POST" class="space-y-2.5">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Nome da Empresa *</label>
                        <input type="text" id="empresa" placeholder="Empresa LTDA" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Nome do Responsável *</label>
                        <input type="text" id="responsavel" placeholder="João Silva" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">E-mail *</label>
                        <input type="email" id="email" placeholder="email@gmail.com" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Matricula *</label>
                        <input type="text" id="matricula" placeholder="M29L0" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">CNPJ *</label>
                        <input type="text" id="cnpj" placeholder="00.000.000/0000-00" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">CPF *</label>
                        <input type="text" id="cpf" placeholder="000.000.000-00" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Senha *</label>
                        <input type="password" id="senha" placeholder="••••••••" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-0.5">Confirmar Senha *</label>
                        <input type="password" id="confirmaSenha" placeholder="••••••••" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
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
                    <input type="checkbox" id="terms" class="rounded border-slate-300 text-blue-900" required>
                    <label for="terms" class="text-[11px] text-slate-600">
                        Eu aceito os 
                        <a href="../termosepolitica.php/termos.php" target="_blank" class="text-blue-900 font-bold underline">Termos</a> 
                        e a 
                        <a href="../termosepolitica.php/política.php" target="_blank" class="text-blue-900 font-bold underline">Política</a>
                    </label>
                </div>

                <button type="submit" class="w-full bg-[#0a1b2e] hover:bg-[#1a2f45] text-white font-bold py-2 rounded-lg transition-all shadow-md text-sm">
                    Criar Conta
                </button>
            </form>

            <p class="text-center text-[11px] text-slate-500 mt-3">Já tem uma conta? <a href="#" class="text-blue-900 font-bold hover:underline">Fazer login</a></p>
        </div>

        <p class="text-center text-[10px] text-slate-400 mt-3">© <?php echo date('Y'); ?> MetaCash. Todos os direitos reservados.</p>
    </div>

    <script>
        const form = document.getElementById('signupForm');
        const senhaInput = document.getElementById('senha');
        const confirmaSenhaInput = document.getElementById('confirmaSenha');

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const cpfRegex = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
        const cnpjRegex = /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/;

        senhaInput.addEventListener('input', () => {
            const val = senhaInput.value;
            updateReq('req-length', val.length >= 8);
            updateReq('req-upper', /[A-Z]/.test(val));
            updateReq('req-lower', /[a-z]/.test(val));
            updateReq('req-num', /\d/.test(val));
            updateReq('req-special', /[!@#$%^&*(),.?":{}|<>]/.test(val));
        });

        function updateReq(id, isValid) {
            const el = document.getElementById(id);
            const icon = el.querySelector('i');
            if (isValid) {
                el.classList.replace('text-slate-500', 'text-green-600');
                icon.classList.replace('fa-times', 'fa-check');
                icon.classList.replace('text-red-400', 'text-green-600');
            } else {
                el.classList.replace('text-green-600', 'text-slate-500');
                icon.classList.replace('fa-check', 'fa-times');
                icon.classList.replace('text-green-600', 'text-red-400');
            }
        }

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            if (!emailRegex.test(document.getElementById('email').value)) {
                return alert("Por favor, insira um e-mail válido.");
            }
            
            if (!cpfRegex.test(document.getElementById('cpf').value)) {
                return alert("CPF inválido! Use o formato 000.000.000-00");
            }

            if (!cnpjRegex.test(document.getElementById('cnpj').value)) {
                return alert("CNPJ inválido! Use o formato 00.000.000/0000-00");
            }

            const s = senhaInput.value;
            if (s.length < 8 || !/[A-Z]/.test(s) || !/[a-z]/.test(s) || !/\d/.test(s) || !/[!@#$%^&*(),.?":{}|<>]/.test(s)) {
                return alert("A senha não atende aos requisitos mínimos.");
            }

            if (s !== confirmaSenhaInput.value) {
                return alert("As senhas não coincidem!");
            }

            alert("Cadastro realizado com sucesso!");
            form.submit();
        });
    </script>
</body>
</html>