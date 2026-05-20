<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Gestão Financeira</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="HomeG.css/style.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        

        <main class="flex-1 p-10 flex items-center justify-center h-screen">
            <div class="w-full max-w-4xl mx-auto bg-white rounded-3xl shadow-sm p-10">
                <div class="flex flex-col gap-6">
                    <div class="flex items-center gap-4">
                        <img src="./img/logo.png" alt="MetaCash Logo" class="w-14 h-14 rounded-2xl object-cover">
                        <div>
                            <h1 class="text-4xl font-bold text-slate-800">MetaCash</h1>
                            <p class="text-slate-500">Gestão Financeira Empresarial Simples e Eficiente</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="rounded-3xl border border-slate-200 p-8 shadow-sm">
                            <h2 class="text-xl font-semibold text-slate-800">Bem-vindo ao painel do gerente</h2>
                            <p class="text-slate-500 mt-3">Use o menu à esquerda para acessar os principais recursos do MetaCash.</p>
                        </div>
                        <div class="rounded-3xl bg-slate-50 p-8 shadow-sm">
                            <h3 class="font-bold text-slate-800">Acesso rápido</h3>
                            <ul class="mt-4 space-y-3 text-slate-600">
                                <li class="flex items-center gap-3"><i class="fas fa-check text-teal-500"></i> Visão geral das finanças</li>
                                <li class="flex items-center gap-3"><i class="fas fa-check text-teal-500"></i> Gestão de transações</li>
                                <li class="flex items-center gap-3"><i class="fas fa-check text-teal-500"></i> Histórico de alterações</li>
                                <li class="flex items-center gap-3"><i class="fas fa-check text-teal-500"></i> Configurações da conta</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4">
                        <a href="../Login.php/index.php" class="flex-1 text-center bg-[#0f172a] text-white py-4 rounded-2xl font-bold hover:bg-slate-900 transition">Entrar</a>
                        <a href="../LoginGerente.php/index.php" class="flex-1 text-center border border-slate-300 py-4 rounded-2xl font-bold text-slate-700 hover:bg-slate-100 transition">Entrar como gerente</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>