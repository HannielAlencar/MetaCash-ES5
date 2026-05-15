<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MetaCash - Gestão</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-teal { color: #2dd4bf; }
        .text-red { color: #f87171; }
        .bg-custom-dark { background-color: #0f172a; }
    </style>
</head>
<body class="bg-gray-50">

    <div class="flex">
<aside class="flex flex-col min-h-screen w-64 bg-[#0f172a] text-white p-4">
    <div class="flex items-center gap-3 mb-8 px-2">
        <div class="bg-[#2dd4bf] p-2 rounded-lg text-[#0f172a]">
            <i class="fas fa-chart-line text-xl"></i>
        </div>
        <div class="flex flex-col">
            <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
            <span class="text-[10px] text-gray-400">Gestão Empresarial</span>
        </div>
    </div>

    <nav class="flex-1 space-y-1">
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
            <i class="fas fa-th-large w-5"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#29aeb0] text-white shadow-lg">
            <i class="fas fa-exchange-alt w-5"></i>
            <span class="font-medium">Transações</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
            <i class="fas fa-file-alt w-5"></i>
            <span class="font-medium">Relatórios</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
            <i class="fas fa-users w-5"></i>
            <span class="font-medium">Equipe</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
            <i class="fas fa-dollar-sign w-5"></i>
            <span class="font-medium">Dados Financeiros</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
            <i class="fas fa-history w-5"></i>
            <span class="font-medium">Histórico</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition">
            <i class="fas fa-cog w-5"></i>
            <span class="font-medium">Configurações</span>
        </a>
    </nav>

    <div class="mt-auto pt-6 border-t border-slate-800">
        <div class="flex items-center gap-3 p-3 bg-[#1e3a5f]/50 rounded-xl mb-4">
            <div class="w-10 h-10 min-w-[40px] rounded-full bg-[#2dd4bf] flex items-center justify-center text-[#0f172a] font-bold">
                E
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-semibold truncate text-white">Empresa LTDA</p>
                <p class="text-[10px] text-gray-400 truncate">empresa@exemplo.com</p>
            </div>
        </div>
        
        <button class="flex items-center gap-3 px-4 py-2 text-gray-400 hover:text-white transition w-full">
            <i class="fas fa-sign-out-alt w-5 text-left"></i>
            <span class="font-medium">Sair</span>
        </button>
    </div>
</aside>

        <main class="flex-1 p-10">
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800">Transações</h1>
                    <p class="text-slate-500">Gerencie todas as transações financeiras</p>
                </div>
                <button class="bg-[#2dd4bf] text-white px-6 py-3 rounded-lg font-bold hover:bg-teal-600 transition shadow-md">
                    + Adicionar Transação
                </button>
            </header>

            <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-gray-500 text-sm font-medium mb-2">Total de Receitas</p>
                    <h2 class="text-3xl font-bold text-[#2dd4bf]">R$ 95.500</h2>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-gray-500 text-sm font-medium mb-2">Total de Despesas</p>
                    <h2 class="text-3xl font-bold text-red-400">R$ 66.700</h2>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-gray-500 text-sm font-medium mb-2">Saldo do Período</p>
                    <h2 class="text-3xl font-bold text-slate-800">R$ 28.800</h2>
                </div>
            </section>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800">Transações Recentes</h3>
                    <a href="#" class="text-[#2dd4bf] text-sm font-bold hover:underline">Ver todas</a>
                </div>

                <div class="divide-y divide-gray-50">
                    <?php
                    $dados = [
                        ['label' => 'Venda Cliente XYZ', 'sub' => 'Salário • 01/03/2026', 'valor' => 45000.00, 'icon' => 'fa-arrow-up'],
                        ['label' => 'Fornecedor ABC', 'sub' => 'Compras • 02/03/2026', 'valor' => -15000.00, 'icon' => 'fa-shopping-cart'],
                        ['label' => 'Aluguel Escritório', 'sub' => 'Aluguel • 05/03/2026', 'valor' => -8500.00, 'icon' => 'fa-home'],
                        ['label' => 'Venda Cliente LMN', 'sub' => 'Salário • 09/03/2026', 'valor' => 32000.00, 'icon' => 'fa-arrow-up'],
                        // Adicione mais itens aqui para testar a rolagem
                    ];

                    foreach ($dados as $item):
                        $isPositive = $item['valor'] > 0;
                    ?>
                    <div class="flex justify-between items-center p-6 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-slate-500 text-sm">
                                <i class="fas <?= $item['icon'] ?>"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800"><?= $item['label'] ?></p>
                                <p class="text-xs text-slate-400"><?= $item['sub'] ?></p>
                            </div>
                        </div>
                        <div class="font-bold <?= $isPositive ? 'text-[#2dd4bf]' : 'text-red-400' ?>">
                            <?= ($isPositive ? '+' : '-') . ' R$ ' . number_format(abs($item['valor']), 2, ',', '.') ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

</body>
</html>