<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config.php'; 

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Trava de segurança tratada para o ambiente local
if (isset($_SESSION['id_usuario'])) {
    if ($_SESSION['nivel_permissao'] !== 'Gerente') {
        header("Location: dashboardGerente.php");
        exit();
    }
}

if (!isset($equipe) || !is_array($equipe) || empty($equipe)) {
    $equipe = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Equipe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/gerenciaEquipe.css">
    
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        <?php require_once '../includes/sidebarGerente.php'; ?>

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="flex-1 p-10 ml-64">
            <header class="flex justify-between items-center mb-8 pb-6 border-b border-gray-200">
                <div>
                    <h1 class="text-4xl font-extrabold text-[#0f172a] tracking-tight">Equipe</h1>
                    <p class="text-lg text-[#334155] mt-2">Gerencie os membros e permissões da equipe</p>
                </div>
                <button class="bg-[#2dd4bf] hover:bg-teal-500 text-[#0f172a] px-6 py-3 rounded-xl font-bold transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <i class="fa-solid fa-plus"></i> Adicionar Membro
                </button>
            </header>

            <!-- Barra de Filtros -->
            <section class="flex flex-col md:flex-row gap-4 p-4 bg-white rounded-2xl border border-gray-200 shadow-sm items-center mb-8">
                <div class="relative flex-1 w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="inputBusca" placeholder="Buscar por nome ou e-mail..." class="w-full pl-12 pr-4 py-3 rounded-xl border-none bg-gray-50 outline-none focus:ring-2 focus:ring-teal-500 transition text-sm">
                </div>
                <div class="relative w-full md:w-auto">
                    <select id="filtroCargo" class="w-full md:w-48 pl-10 pr-8 py-3 rounded-xl border-none bg-gray-50 appearance-none outline-none focus:ring-2 focus:ring-teal-500 transition cursor-pointer text-sm">
                        <option value="todos">Todos os Cargos</option>
                        <option value="Gerente">Gerente</option>
                        <option value="Membro">Membro</option>
                    </select>
                    <i class="fa-solid fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
            </section>

            <!-- Grid de Membros -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gridMembros">
                <?php foreach ($equipe as $membro): ?>
                    <div class="card-membro bg-white p-6 rounded-2xl border border-slate-200 hover:border-[#2dd4bf] hover:shadow-md transition duration-200 flex flex-col justify-between"
                         data-nome="<?= htmlspecialchars(strtolower($membro['nome'])) ?>"
                         data-cargo="<?= htmlspecialchars(strtolower($membro['cargo'])) ?>">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center font-bold text-lg border border-teal-100">
                                    <?php echo $membro['sigla']; ?>
                                </div>
                                <div class="overflow-hidden">
                                    <h3 class="font-bold text-slate-800 text-base truncate"><?php echo $membro['nome']; ?></h3>
                                    <p class="text-xs text-slate-400 truncate"><?php echo $membro['email']; ?></p>
                                </div>
                            </div>
                            <button class="text-slate-400 hover:text-slate-600 p-1.5 transition">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold <?php echo (strtolower($membro['cargo']) == 'gerente') ? 'bg-teal-100 text-teal-800' : 'bg-sky-100 text-sky-800'; ?>">
                                <i class="fa-solid fa-user-gear text-[10px]"></i> <?php echo $membro['cargo']; ?>
                            </span>
                            <button class="text-xs text-red-500 hover:text-red-700 font-bold transition">Remover</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
            
            <div id="msgVazio" class="hidden p-20 text-center text-slate-400">
                <i class="fas fa-search fa-3x mb-4 block opacity-20"></i>
                Nenhum membro da equipe encontrado.
            </div>
        </main>
    </div>
    <script src="../assets/js/gerenciaEquipe.js"></script>
</body>
</html>