<?php
// index.php

// Simulação de dados (Poderia vir de um banco de dados)
$registros = [
    ['tag' => 'Criação', 'tag_color' => 'bg-green-100 text-green-600', 'cat' => 'Transação', 'desc' => 'Nova transação de receita: Venda de Produtos', 'data' => '13/03/2026'],
    ['tag' => 'Edição', 'tag_color' => 'bg-blue-100 text-blue-600', 'cat' => 'Configurações', 'desc' => 'Atualização das configurações de empresa', 'data' => '14/03/2026'],
    ['tag' => 'Criação', 'tag_color' => 'bg-green-100 text-green-600', 'cat' => 'Membro da Equipe', 'desc' => 'Novo membro adicionado à equipe: Maria Santos', 'data' => '15/03/2026'],
    ['tag' => 'Edição', 'tag_color' => 'bg-blue-100 text-blue-600', 'cat' => 'Transação', 'desc' => 'Transação editada: Atualização de valor', 'data' => '16/03/2026'],
    ['tag' => 'Exclusão', 'tag_color' => 'bg-red-100 text-red-600', 'cat' => 'Transação', 'desc' => 'Transação excluída: Duplicada', 'data' => '17/03/2026'],
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Histórico de Alterações</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="historico.css/style.css">
</head>
<body class="flex min-h-screen">

    <aside class="w-64 sidebar text-slate-300 flex flex-col p-4 space-y-6">
        <div class="flex items-center space-x-2 px-2 py-4">
            <div class="bg-teal-500 p-2 rounded-lg text-white">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div>
                <h1 class="font-bold text-white leading-none">MetaCash</h1>
                <span class="text-xs text-slate-400">Gestão Empresarial</span>
            </div>
        </div>

        <nav class="flex-1 space-y-1">
            <a href="#" class="flex items-center space-x-3 px-3 py-2 hover:text-white"><i class="fas fa-th-large w-5"></i> <span>Dashboard</span></a>
            <a href="#" class="flex items-center space-x-3 px-3 py-2 hover:text-white"><i class="fas fa-exchange-alt w-5"></i> <span>Transações</span></a>
            <a href="#" class="flex items-center space-x-3 px-3 py-2 hover:text-white"><i class="fas fa-users w-5"></i> <span>Equipe</span></a>
            <a href="#" class="flex items-center space-x-3 px-3 py-2 hover:text-white"><i class="fas fa-file-alt w-5"></i> <span>Gerenciar Páginas</span></a>
            <a href="#" class="flex items-center space-x-3 px-3 py-2 active-nav"><i class="fas fa-history w-5"></i> <span>Histórico</span></a>
            <a href="#" class="flex items-center space-x-3 px-3 py-2 hover:text-white"><i class="fas fa-cog w-5"></i> <span>Configurações</span></a>
        </nav>

        <button class="w-full bg-slate-700 hover:bg-slate-600 text-white py-2 rounded-lg text-sm">Baixar Relatório</button>

        <div class="pt-4 border-t border-slate-700">
            <div class="bg-slate-800 p-3 rounded-xl flex items-center space-x-3">
                <div class="bg-teal-500 w-8 h-8 rounded-full flex items-center justify-center text-white"><i class="fas fa-user"></i></div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-white truncate">Usuário</p>
                    <p class="text-[10px] text-slate-400 truncate">usuario@exemplo.com</p>
                </div>
            </div>
            <a href="#" class="flex items-center space-x-2 mt-4 px-3 text-sm hover:text-white"><i class="fas fa-sign-out-alt"></i> <span>Sair</span></a>
        </div>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto">
        <header class="mb-8">
            <div class="flex items-center space-x-2 text-teal-600 mb-1">
                <i class="fas fa-history text-2xl"></i>
                <h2 class="text-2xl font-bold text-slate-800">Histórico de Alterações</h2>
            </div>
            <p class="text-slate-500 text-sm">Registro completo de todas as modificações na plataforma</p>
        </header>

        <section class="card p-6 mb-6">
            <div class="flex items-center space-x-2 mb-4 text-teal-600 font-medium">
                <i class="fas fa-filter"></i> <span>Filtros</span>
            </div>
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-7">
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Buscar</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                        <input type="text" placeholder="Descrição, usuário ou e-mail..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 outline-none text-sm">
                    </div>
                </div>
                <div class="col-span-3">
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Tipo de Alteração</label>
                    <select class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 outline-none text-sm bg-white">
                        <option>Todos os tipos</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Data de Alteração</label>
                    <input type="text" placeholder="dd/mm/aaaa" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 outline-none text-sm">
                </div>
            </div>
        </section>

        <section class="card overflow-hidden">
            <div class="bg-[#1e293b] text-white px-6 py-3 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-list-alt"></i>
                    <span class="font-bold text-sm">Registros de Alterações</span>
                </div>
                <span class="text-xs text-slate-400"><?php echo count($registros); ?> registros</span>
            </div>

            <div class="divide-y">
                <?php foreach ($registros as $reg): ?>
                <div class="p-4 flex justify-between items-start hover:bg-slate-50 transition-colors">
                    <div class="space-y-2">
                        <div class="flex space-x-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?= $reg['tag_color'] ?>"><?= $reg['tag'] ?></span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-sky-100 text-sky-600"><?= $reg['cat'] ?></span>
                        </div>
                        <p class="text-sm font-semibold text-slate-700"><?= $reg['desc'] ?></p>
                        <div class="flex items-center space-x-4 text-xs text-slate-400">
                            <span class="flex items-center space-x-1"><i class="far fa-user"></i> <span>João Silva</span></span>
                            <span class="flex items-center space-x-1"><i class="far fa-clock"></i> <span><?= $reg['data'] ?>, 18:14:13</span></span>
                        </div>
                    </div>
                    <button class="text-red-400 hover:text-red-600"><i class="fas fa-trash-alt"></i></button>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>
</html>