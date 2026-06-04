<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';

$empresa = 'Empresa LTDA';
$email_empresa = 'empresa@exemplo.com';

// Dados simulados
$labels_meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'];
$dados_receita = [15000, 18000, 21000, 19500, 22000, 25000];
$dados_despesa = [12000, 14000, 16500, 15000, 18000, 20000];

$relatorios_recentes = [
    ['nome' => 'Relatório Mensal - Maio 2026', 'tipo' => 'DRE', 'data' => '31/05/2026'],
    ['nome' => 'Análise de Fluxo de Caixa', 'tipo' => 'Fluxo', 'data' => '28/05/2026'],
    ['nome' => 'Despesas por Categoria', 'tipo' => 'Detalhado', 'data' => '25/05/2026'],
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MetaCash - Relatórios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <div class="flex min-h-screen">
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
                <a href="gerenciaPaginas.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
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

        <main class="flex-1 p-10 ml-64">
            <header class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">Relatórios Financeiros</h1>
                <p class="text-slate-500 mt-1 text-sm">Análises detalhadas e relatórios personalizados</p>
            </header>

            <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition cursor-pointer">
                    <i class="fas fa-file-invoice text-3xl text-blue-600 mb-4"></i>
                    <h3 class="font-bold text-lg text-slate-800">Gerar DRE</h3>
                    <p class="text-sm text-slate-500">Demonstrativo de Resultados</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition cursor-pointer">
                    <i class="fas fa-chart-line text-3xl text-teal-600 mb-4"></i>
                    <h3 class="font-bold text-lg text-slate-800">Fluxo de Caixa</h3>
                    <p class="text-sm text-slate-500">Análise de entradas e saídas</p>
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border border-slate-200">
                    <h4 class="font-bold text-slate-800 mb-4">Receitas vs Despesas</h4>
                    <canvas id="mainChart" height="300"></canvas>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200">
                    <h4 class="font-bold text-slate-800 mb-4">Despesas por Categoria</h4>
                    <canvas id="pieChart" height="300"></canvas>
                </div>
            </div>

            <section class="bg-white rounded-2xl border border-slate-200 p-6">
                <h4 class="font-bold text-slate-800 mb-6">Relatórios Recentes</h4>
                <div class="space-y-4">
                    <?php foreach($relatorios_recentes as $rel): ?>
                        <div class="flex justify-between items-center p-4 border border-slate-100 rounded-xl hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-file-alt text-slate-400 text-lg"></i>
                                <div>
                                    <p class="font-bold text-slate-800"><?php echo $rel['nome']; ?></p>
                                    <p class="text-xs text-slate-400"><?php echo $rel['tipo']; ?> • <?php echo $rel['data']; ?></p>
                                </div>
                            </div>
                            <a href="#" class="text-teal-600 hover:text-teal-700 font-semibold text-sm"><i class="fas fa-download"></i> Baixar</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    </div>

    <script>
    new Chart(document.getElementById('mainChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labels_meses); ?>,
            datasets: [{
                label: 'Receitas',
                data: <?php echo json_encode($dados_receita); ?>,
                borderColor: '#2dd4bf',
                backgroundColor: 'rgba(45, 212, 191, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Despesas',
                data: <?php echo json_encode($dados_despesa); ?>,
                borderColor: '#64748b',
                fill: false,
                tension: 0.4
            }]
        }
    });

    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: ['Salários', 'Fornecedores', 'Aluguel'],
            datasets: [{
                data: [5000, 4000, 2000],
                backgroundColor: ['#1e293b', '#475569', '#2dd4bf']
            }]
        }
    });
    </script>
</body>
</html>