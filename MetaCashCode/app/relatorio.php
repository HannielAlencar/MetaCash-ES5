<<?php include '../../../config.php';?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MetaCash - Gestão</title>
    <link rel="stylesheet" href="Relatorio.css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <aside class="sidebar">
        <div class="logo-container">
            <i class="fas fa-chart-pie logo-icon"></i>
            <span>MetaCash</span>
        </div>
        
        <nav class="nav-menu">
            <a href="#" class="nav-item"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="#" class="nav-item active"><i class="fas fa-file-alt"></i> Relatórios</a>
            <a href="#" class="nav-item"><i class="fas fa-users"></i> Equipe</a>
        </nav>

        <div class="user-profile" style="margin-top: auto; border-top: 1px solid #334155; pt: 20px;">
            <p style="color: white; font-size: 14px;"><?= $empresa ?></p>
            <p style="font-size: 10px;"><?= $email_empresa ?></p>
        </div>
    </aside>

    <main class="content">
        <header>
            <h1>Relatórios Financeiros</h1>
            <p>Análises detalhadas e relatórios personalizados</p>
        </header>

        <section class="action-cards">
            <div class="card-btn blue">
                <i class="fas fa-file-invoice"></i>
                <h3>Gerar DRE</h3>
                <small>Demonstrativo de Resultados</small>
            </div>
            <div class="card-btn teal">
                <i class="fas fa-chart-line"></i>
                <h3>Fluxo de Caixa</h3>
                <small>Análise de entradas e saídas</small>
            </div>
        </section>

        <section class="charts-grid">
            <div class="chart-container">
                <h4 style="margin-bottom: 20px;">Receitas vs Despesas</h4>
                <canvas id="mainChart"></canvas>
            </div>
            <div class="chart-container">
                <h4 style="margin-bottom: 20px;">Despesas por Categoria</h4>
                <canvas id="pieChart"></canvas>
            </div>
        </section>

        <section class="reports-list">
            <h4 style="margin-bottom: 20px;">Relatórios Recentes</h4>
            <?php foreach($relatorios_recentes as $rel): ?>
            <div class="report-item">
                <div style="display: flex; gap: 15px; align-items: center;">
                    <i class="fas fa-file-alt" style="color: #94a3b8; font-size: 20px;"></i>
                    <div>
                        <p style="font-weight: bold; font-size: 14px;"><?= $rel['nome'] ?></p>
                        <p style="font-size: 11px; color: #94a3b8;"><?= $rel['tipo'] ?> • <?= $rel['data'] ?></p>
                    </div>
                </div>
                <a href="#" class="download-btn"><i class="fas fa-download"></i> Baixar</a>
            </div>
            <?php endforeach; ?>
        </section>
    </main>

    <script>
        // Gráfico de Linha
        new Chart(document.getElementById('mainChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode($labels_meses) ?>,
                datasets: [{
                    label: 'Receitas',
                    data: <?= json_encode($receita) ?>,
                    borderColor: '#2dd4bf',
                    backgroundColor: 'rgba(45, 212, 191, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Despesas',
                    data: <?= json_encode($despesa) ?>,
                    borderColor: '#1e293b',
                    fill: false,
                    tension: 0.4
                }]
            }
        });

        // Gráfico de Pizza
        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: ['Adm', 'Salário', 'Compras'],
                datasets: [{
                    data: [39, 37, 24],
                    backgroundColor: ['#111827', '#1e293b', '#2dd4bf']
                }]
            }
        });
    </script>
</body>
</html>