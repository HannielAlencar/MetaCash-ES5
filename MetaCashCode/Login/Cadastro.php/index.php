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
<body class="min-h-screen relative text-slate-800">
    <div class="noise-overlay"></div>

    <div class="relative z-10 container mx-auto px-4 py-10 max-w-2xl">
        <!-- Header Voltar -->
        <a href="#" class="inline-flex items-center gap-2 text-white font-medium mb-8 hover:text-slate-200 transition-colors">
            <i class="fas fa-arrow-left text-sm"></i> Voltar
        </a>

        <!-- Logo MetaCash -->
        <div class="flex flex-col items-center mb-8">
            <div class="mb-4">
                <!-- Substitua 'logo-metacash.png' pelo caminho do seu arquivo de imagem -->
                <img src="/MetaCashCode/Usuario/Dashboard/img/LogoAzulEscuro.png" alt="Logo MetaCash" class="w-16 h-16 object-contain">
            </div>
            <h1 class="text-white text-2xl font-bold tracking-tight">MetaCash</h1>
            <p class="text-slate-300 text-sm font-medium">Cadastre sua empresa</p>
        </div>

        <!-- Card Form -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold mb-6">Criar Conta</h2>
            
            <form action="#" method="POST" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Nome da Empresa *</label>
                        <input type="text" placeholder="Empresa LTDA" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Nome do Responsável *</label>
                        <input type="text" placeholder="João Silva" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">E-mail *</label>
                        <input type="email" placeholder="email@gmail.com" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Matricula *</label>
                        <input type="text" placeholder="M29L0" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">CNPJ *</label>
                        <input type="text" placeholder="00.000.000/0000-00" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">CPF *</label>
                        <input type="text" placeholder="000.000.000-00" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Senha *</label>
                        <input type="password" placeholder="••••••••" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Confirmar Senha *</label>
                        <input type="password" placeholder="••••••••" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <!-- Requisitos Senha -->
                <div class="bg-slate-50 border border-slate-100 rounded-lg p-4 mt-2">
                    <p class="text-xs font-bold text-slate-700 mb-2">Requisitos de Senha:</p>
                    <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-500">
                        <p><i class="fas fa-times text-red-400 mr-1"></i> Mínimo de 8 caracteres</p>
                        <p><i class="fas fa-times text-red-400 mr-1"></i> Letra maiúscula (A-Z)</p>
                        <p><i class="fas fa-times text-red-400 mr-1"></i> Letra minúscula (a-z)</p>
                        <p><i class="fas fa-times text-red-400 mr-1"></i> Número (0-9)</p>
                        <p><i class="fas fa-times text-red-400 mr-1"></i> Caractere especial (!@#$)</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 py-2">
                    <input type="checkbox" id="terms" class="rounded border-slate-300 text-blue-900">
                    <label for="terms" class="text-xs text-slate-600">Eu aceito os <a href="#" class="text-blue-900 font-bold underline">Termos de Uso</a> e a <a href="#" class="text-blue-900 font-bold underline">Política de Privacidade</a></label>
                </div>

                <button type="submit" class="w-full bg-[#0a1b2e] hover:bg-[#1a2f45] text-white font-bold py-3 rounded-lg transition-all shadow-md">
                    Criar Conta
                </button>
            </form>

            <p class="text-center text-xs text-slate-500 mt-4">Já tem uma conta? <a href="#" class="text-blue-900 font-bold hover:underline">Fazer login</a></p>
        </div>

        <p class="text-center text-[10px] text-slate-400 mt-8">© <?php echo date('Y'); ?> MetaCash. Todos os direitos reservados.</p>
    </div>
</body>
</html>