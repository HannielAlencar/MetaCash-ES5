// Utilitário para formatar moeda
const currencyFormatter = new Intl.NumberFormat('pt-BR', { 
    style: 'currency', 
    currency: 'BRL' 
});

// Função global para abrir/fechar modais
function toggleModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.toggle('hidden');
        modal.classList.toggle('flex');
    }
}

// Inicialização dos Gráficos após o carregamento da página
window.addEventListener('DOMContentLoaded', function() {
    const chartDataElement = document.getElementById('chart-data');
    if (!chartDataElement) {
        console.error('Dados do grafico nao encontrados.');
        return;
    }

    const chartDataUsuario = JSON.parse(chartDataElement.textContent || '{}');

    // 1. Gráfico de Linha (Desempenho Mensal)
    const canvasLinha = document.getElementById('chartLinha');
    if (canvasLinha) {
        const ctxLinha = canvasLinha.getContext('2d');
        new Chart(ctxLinha, {
            type: 'line',
            data: {
                labels: chartDataUsuario.labels,
                datasets: [
                    {
                        label: 'Receitas',
                        data: chartDataUsuario.receitas,
                        borderColor: '#2dd4bf',
                        backgroundColor: 'rgba(45, 212, 191, 0.1)',
                        fill: true, 
                        tension: 0.4
                    }, 
                    {
                        label: 'Despesas',
                        data: chartDataUsuario.despesas,
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244, 63, 94, 0.05)',
                        fill: true, 
                        tension: 0.4
                    }, 
                    {
                        label: 'Resultado (R - D)',
                        data: chartDataUsuario.lucro,
                        borderColor: '#3b82f6', 
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: false, 
                        borderDash: [5, 5], 
                        tension: 0.4
                    }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    label += currencyFormatter.format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Gráfico de Pizza (Distribuição por Categoria)
    const canvasPizza = document.getElementById('chartPizza');
    if (canvasPizza) {
        const ctxPizza = canvasPizza.getContext('2d');
        
        // Garante que os valores fiquem positivos (absolutos) para o gráfico de rosca não dar erro visual
        const dadosPuros = chartDataUsuario.catValores.map(v => Math.abs(v));
        
        new Chart(ctxPizza, {
            type: 'doughnut',
            data: {
                labels: chartDataUsuario.catLabels,
                datasets: [{
                    data: dadosPuros,
                    backgroundColor: ['#0f172a', '#2dd4bf', '#3b82f6', '#8b5cf6', '#f43f5e'],
                    borderWidth: 0
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                cutout: '75%',
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                const valorReal = chartDataUsuario.catValores[index];
                                const label = context.label || '';
                                return `${label}: ${currencyFormatter.format(valorReal)}`;
                            }
                        }
                    }
                }
            }
        });
    }
});