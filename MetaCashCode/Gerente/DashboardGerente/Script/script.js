// scripts.js

document.addEventListener("DOMContentLoaded", function() {
    // 1. Recuperar dados do HTML (passados pelo PHP)
    const chartData = JSON.parse(document.getElementById('financeData').textContent);

    // 2. Configurações Globais
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';

    // 3. Gráfico de Linha (Receitas vs Despesas)
    const ctxLine = document.getElementById('graficoLinha').getContext('2d');
    const gradientReceita = ctxLine.createLinearGradient(0, 0, 0, 400);
    gradientReceita.addColorStop(0, 'rgba(20, 184, 166, 0.4)');
    gradientReceita.addColorStop(1, 'rgba(20, 184, 166, 0)');

    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Receitas',
                    data: chartData.receitas,
                    borderColor: '#14b8a6',
                    borderWidth: 3,
                    backgroundColor: gradientReceita,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0
                },
                {
                    label: 'Despesas',
                    data: chartData.despesas,
                    borderColor: '#0f172a',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    pointRadius: 0,
                    borderDash: [5, 5]
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { borderDash: [2, 2] } },
                x: { grid: { display: false } }
            }
        }
    });

    // 4. Gráfico de Pizza (Categorias)
    const ctxPie = document.getElementById('graficoPizza').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: Object.keys(chartData.categorias),
            datasets: [{
                data: Object.values(chartData.categorias),
                backgroundColor: ['#0f172a', '#1e293b', '#14b8a6', '#2dd4bf', '#94a3b8'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: { usePointStyle: true, padding: 20 }
                }
            }
        }
    });
});