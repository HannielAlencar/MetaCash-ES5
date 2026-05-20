<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Gestão Financeira</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            /* Recriação do fundo escuro com gradiente radial idêntico ao Figma */
            background: radial-gradient(circle at 50% 50%, #2b6a7a 0%, #152c3f 50%, #0a111a 100%);
            background-attachment: fixed;
        }
        
        /* Aplicação da textura de ruído (noise) para fidelidade 100% à imagem */
        .noise-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            opacity: 0.15;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-white relative">

    <!-- Camada de textura (noise) -->
    <div class="noise-overlay"></div>

    <!-- Container Principal (z-10 para ficar acima do ruído) -->
    <div class="relative z-10 flex flex-col min-h-screen p-8">
        
        <!-- Header: Voltar -->
        <header>
            <a href="javascript:history.back()" class="inline-flex items-center gap-3 text-lg font-medium text-white hover:text-slate-300 transition-colors">
                <i class="fas fa-arrow-left font-light"></i>
                Voltar
            </a>
        </header>

        <!-- Conteúdo Central -->
        <main class="flex-1 flex flex-col items-center justify-center w-full max-w-3xl mx-auto text-center">
            
            <!-- Logo Icon -->
            <div class="w-[84px] h-[84px] rounded-[1.25rem] bg-gradient-to-b from-[#4ad6b4] to-[#229e9d] flex items-center justify-center shadow-[0_8px_30px_rgb(0,0,0,0.2)] mb-8">
                <span class="text-4xl font-extrabold text-white tracking-tight">M</span>
            </div>

            <!-- Título -->
            <h1 class="text-6xl font-extrabold text-white tracking-tight mb-6">
                MetaCash
            </h1>

            <!-- Subtítulo -->
            <p class="text-[20px] text-slate-300 font-medium mb-10 tracking-wide">
                Gestão Financeira Empresarial Simples e Eficiente
            </p>

            <!-- Botão de Ação -->
            <a href="../../Login/Login.php/index.php" class="bg-white text-[#0f172a] px-10 py-4 rounded-2xl font-bold text-base flex items-center gap-3 hover:bg-slate-100 hover:scale-105 transition-all shadow-lg mb-12">
                Entrar <i class="fas fa-arrow-right text-sm"></i>
            </a>

            <!-- Badge Informativa -->
            <div class="bg-[#0f1c2d]/70 border border-[#1e3a5f]/60 backdrop-blur-md rounded-full px-6 py-3 flex items-center gap-3 shadow-sm">
                <div class="w-2 h-2 rounded-full bg-[#34d399] shadow-[0_0_8px_#34d399]"></div>
                <span class="text-sm font-medium text-slate-200">Controle total das suas finanças</span>
            </div>

        </main>

        <!-- Footer -->
        <footer class="text-center text-[11px] text-slate-400 font-medium mt-auto">
            © <?php echo date('Y'); ?> MetaCash. Todos os direitos reservados.
        </footer>

    </div>

</body>
</html>