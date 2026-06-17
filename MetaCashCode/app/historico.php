<?php
// 1. Segurança e Sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php'; 
// LÓGICA DE DADOS DE FALLBACK 
$registros = [];

// Garante 100% de certeza que $registros é um array válido para evitar o TypeError no count()
if (!is_array($registros)) {
    $registros = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Histórico</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    meta: {
                        menu: 'var(--meta-menu)',
                        btn1: 'var(--meta-btn1)',
                        destaque: 'var(--meta-destaque)',
                        btn2: 'var(--meta-btn2)',
                        clara: 'var(--meta-clara)',
                        fundo: 'var(--meta-fundo)',
                    }
                }
            }
        }
    }
    </script>
    <script>
    const temaPadrao = { menu: '#0F2440', btn1: '#204C73', destaque: '#24A6B6', btn2: '#35C59A', clara: '#5DA4C0', fundo: '#FDFEFB' };
    const temaSalvo = JSON.parse(localStorage.getItem('metaCashTheme')) || temaPadrao;
    const raiz = document.documentElement;
    raiz.style.setProperty('--meta-menu', temaSalvo.menu);
    raiz.style.setProperty('--meta-btn1', temaSalvo.btn1);
    raiz.style.setProperty('--meta-destaque', temaSalvo.destaque);
    raiz.style.setProperty('--meta-btn2', temaSalvo.btn2);
    raiz.style.setProperty('--meta-clara', temaSalvo.clara);
    raiz.style.setProperty('--meta-fundo', temaSalvo.fundo);
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="historico.css/style.css">
    <style>
        .sidebar a:hover { color: white; }
    </style>
</head>
<body class="bg-meta-fundo transition-colors duration-300">
    <div class="flex min-h-screen">
        <?php require_once '../includes/sidebarGerente.php'; ?>

            <!-- Profile Footer -->
            <div class="mt-auto pt-6 border-t border-white/10 space-y-4 pb-2">
                <a href="../app/PerfilGerente.php" class="bg-meta-btn1/40 p-3 rounded-2xl flex items-center gap-3 border border-white/5 hover:bg-meta-btn1/60 transition block group">
                    <div class="w-10 h-10 bg-meta-destaque rounded-full flex items-center justify-center text-white font-bold text-lg shrink-0 group-hover:scale-105 transition-transform duration-300">U</div>
                    <div class="flex flex-col overflow-hidden">
                        <span class="text-sm font-bold truncate">Usuário</span>
                        <span class="text-[10px] text-gray-400 truncate">usuario@exemplo.com</span>
                    </div>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-400 hover:text-white transition group">
                    <i class="fas fa-sign-out-alt rotate-180 group-hover:text-red-400 transition-colors"></i>
                    <span class="font-medium">Sair</span>
                </a>
            </div>
        </aside>

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="flex-1 p-10 ml-64">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-4xl font-extrabold text-meta-menu tracking-tight transition-colors duration-300">Histórico de Alterações</h1>
                    <p class="text-sm text-slate-500 mt-2">Acompanhe todas as mudanças registradas no sistema.</p>
                </div>
            </div>

            <!-- Filtros de Busca -->
            <section class="mb-6 p-6 bg-white rounded-3xl shadow-sm border border-gray-200">
                <div class="grid grid-cols-12 gap-4 items-end">
                    <div class="col-span-7">
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Buscar</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" id="inputBusca" placeholder="Descrição, tipo ou data..." class="w-full pl-12 pr-4 py-3 border rounded-2xl outline-none focus:ring-2 focus:ring-meta-destaque text-sm transition-all">
                        </div>
                    </div>
                    <div class="col-span-3">
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Tipo de Alteração</label>
                        <select id="filtroTipo" class="w-full px-4 py-3 border rounded-2xl outline-none focus:ring-2 focus:ring-meta-destaque text-sm bg-white transition-all">
                            <option value="todos">Todos os tipos</option>
                            <option value="criação">Criação</option>
                            <option value="edição">Edição</option>
                            <option value="exclusão">Exclusão</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Data de Alteração</label>
                        <input type="text" id="filtroData" placeholder="dd/mm/aaaa" class="w-full px-4 py-3 border rounded-2xl outline-none focus:ring-2 focus:ring-meta-destaque text-sm bg-white transition-all">
                    </div>
                </div>
            </section>

            <!-- Tabela de Registros -->
            <section class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-meta-menu text-white px-6 py-4 flex justify-between items-center transition-colors duration-300">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-history"></i>
                        <span class="font-bold uppercase text-sm tracking-wide">Registros de Alterações</span>
                    </div>
                    <span class="text-xs text-slate-300" id="contadorRegistros"><?php echo count($registros); ?> registros</span>
                </div>
                <div class="divide-y" id="containerRegistros">
                    <?php foreach ($registros as $index => $reg): ?>
                        <div class="item-registro p-6 flex flex-col gap-4 lg:flex-row lg:justify-between lg:items-start hover:bg-slate-50 transition"
                             data-desc="<?= strtolower($reg['desc'] ?? '') ?>"
                             data-tipo="<?= strtolower($reg['tag'] ?? '') ?>"
                             data-data="<?= strtolower($reg['data'] ?? '') ?>">
                            <div class="space-y-3">
                                <div class="flex flex-wrap gap-2 items-center">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase <?= $reg['tag_color'] ?? 'bg-slate-100 text-slate-700' ?>"><?= htmlspecialchars($reg['tag'] ?? 'Alteração') ?></span>
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-slate-100 text-slate-700"><?= htmlspecialchars($reg['cat'] ?? 'Sistema') ?></span>
                                </div>
                                <p class="text-sm text-slate-700 font-semibold"><?= htmlspecialchars($reg['desc'] ?? '') ?></p>
                                <div class="flex flex-wrap gap-4 text-xs text-slate-500">
                                    <span class="flex items-center gap-2"><i class="far fa-user"></i> João Silva</span>
                                    <span class="flex items-center gap-2"><i class="far fa-clock"></i><?= htmlspecialchars($reg['data'] ?? date('d/m/Y')) ?>, 18:14:13</span>
                                </div>
                            </div>
                            <button onclick="removerRegistro(this)" class="self-start text-red-500 hover:text-red-700 rounded-full p-2 transition">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div id="msgVazio" class="hidden p-20 text-center text-slate-400">
                    <i class="fas fa-search fa-3x mb-4 block opacity-20"></i>
                    Nenhum registro encontrado para a pesquisa.
                </div>
            </section>
        </main>
    </div>

    <script src="../assets/js/historico.js"></script>
</body>
</html>