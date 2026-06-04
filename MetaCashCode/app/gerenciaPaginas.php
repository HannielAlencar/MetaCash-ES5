<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Gerenciamento de Páginas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 flex min-h-screen">

    <aside class="w-64 bg-[#0f172a] text-white p-4 flex flex-col fixed h-screen shrink-0 z-40">
        <div class="flex items-center gap-3 mb-10 px-2 pt-2">
            <img src="../assets/img/logo.png" alt="MetaCash Logo" class="w-11 h-11 rounded-lg object-cover" onerror="this.src='https://ui-avatars.com/api/?name=MC&background=2dd4bf&color=0f172a'">
            <div class="flex flex-col">
                <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
                <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Gestão Empresarial</span>
            </div>
        </div>

        <nav class="flex-1 space-y-2">
            <a href="dashboardGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                <i class="fas fa-th-large w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="transacoesGerente.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                <i class="fas fa-exchange-alt w-5"></i>
                <span class="font-medium">Transações</span>
            </a>
            <a href="gerenciaEquipe.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                <i class="fas fa-users w-5"></i>
                <span class="font-medium">Equipe</span>
            </a>
            <a href="gerenciaPaginas.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#2dd4bf] text-white shadow-lg transition">
                <i class="fas fa-file-alt w-5"></i>
                <span class="font-medium">Gerenciar Páginas</span>
            </a>
            <a href="historico.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                <i class="fas fa-history w-5"></i>
                <span class="font-medium">Histórico</span>
            </a>
            <a href="configuracao.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                <i class="fas fa-cog w-5"></i>
                <span class="font-medium">Configurações</span>
            </a>
            <button onclick="toggleModal('modalRelatorio')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition border border-transparent hover:border-slate-700 text-left">
                <i class="fas fa-file-pdf w-5"></i>
                <span class="font-medium">Baixar Relatório</span>
            </button>
        </nav>

        <div class="mt-auto pt-6 border-t border-slate-800 space-y-4 pb-2">
            <a href="perfil.php" class="bg-[#1e3a5f]/40 p-3 rounded-2xl flex items-center gap-3 border border-slate-700/50 hover:bg-[#1e3a5f]/60 transition block group">
                <div class="w-10 h-10 bg-[#2dd4bf] rounded-full flex items-center justify-center text-[#0f172a] font-bold text-lg shrink-0 group-hover:scale-105 transition-transform">U</div>
                <div class="flex flex-col overflow-hidden">
                    <span class="text-sm font-bold truncate">Usuário</span>
                    <span class="text-[10px] text-gray-400 truncate">usuario@exemplo.com</span>
                </div>
            </a>
            <a href="../auth/logout.php" class="flex items-center gap-3 px-4 py-2 text-gray-400 hover:text-white transition group">
                <i class="fas fa-sign-out-alt rotate-180 group-hover:text-red-400 transition-colors"></i>
                <span class="font-medium">Sair</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-8">
        <header class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900">Gerenciamento de Páginas</h2>
            <p class="text-slate-500">Configure a visibilidade e ordem das páginas do sistema</p>
        </header>

        <section class="bg-white border border-slate-200 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-teal-600 mt-1"></i>
                <div>
                    <h4 class="font-bold text-slate-800 mb-2">Como Funciona</h4>
                    <p class="text-sm text-slate-600 mb-1">• Use os botões de <strong>ativar/desativar</strong> para controlar quais páginas aparecem no menu</p>
                    <p class="text-sm text-slate-600">• Clique em <strong>"Editar"</strong> para ir diretamente à página e fazer alterações</p>
                </div>
            </div>
        </section>

        <section class="bg-white border border-slate-200 rounded-2xl p-6 mb-6">
            <h3 class="font-bold text-lg mb-6">Páginas Editáveis</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-teal-100 flex items-center justify-center rounded-lg text-teal-600"><i class="fas fa-home"></i></div>
                        <div><p class="font-bold text-slate-800">Home</p><p class="text-xs text-slate-400">Página inicial da empresa com logo e apresentação</p></div>
                    </div>
                    <a href="edicaoHome.php" class="inline-flex items-center justify-center px-4 py-2 bg-teal-500 text-white rounded-lg text-sm font-semibold hover:bg-teal-600"><i class="fas fa-pen mr-2"></i> Editar</a>
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-teal-100 flex items-center justify-center rounded-lg text-teal-600"><i class="fas fa-th-large"></i></div>
                        <div><p class="font-bold text-slate-800">Dashboard</p><p class="text-xs text-slate-400">Visão geral dos dados financeiros e métricas principais</p></div>
                    </div>
                    <a href="edicaoDashboard.php" class="inline-flex items-center justify-center px-4 py-2 bg-teal-500 text-white rounded-lg text-sm font-semibold hover:bg-teal-600"><i class="fas fa-pen mr-2"></i> Editar</a>
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-teal-100 flex items-center justify-center rounded-lg text-teal-600"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div><p class="font-bold text-slate-800">Transações</p><p class="text-xs text-slate-400">Registro e gerenciamento de receitas e despesas</p></div>
                    </div>
                    <a href="edicaoTransacoes.php" class="inline-flex items-center justify-center px-4 py-2 bg-teal-500 text-white rounded-lg text-sm font-semibold hover:bg-teal-600"><i class="fas fa-pen mr-2"></i> Editar</a>
                </div>
            </div>
        </section>
    </main>

    <script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }
    </script>
</body>
</html>