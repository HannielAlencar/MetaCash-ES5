/**
 * SISTEMA METACASH - SCRIPT DE INTERFACE E GRÁFICOS
 * Versão Corrigida com Limpeza de Dados e Cálculos Automáticos
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. GESTÃO DE MODAIS (Simples e Direta)
    const setupModal = (btnId, modalId) => {
        const btn = document.getElementById(btnId);
        const modal = document.getElementById(modalId);
        if (!btn || !modal) return;

        btn.onclick = () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        modal.onclick = (e) => {
            if (e.target === modal || e.target.closest('.btn-fechar') || e.target.getAttribute('data-close') === 'true') {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        };
    };

    setupModal('btnAbrirModal', 'modalTransacao');
    setupModal('btnAbrirRelatorio', 'modalRelatorio');

    // 2. VERIFICAÇÃO E RENDERIZAÇÃO DOS DADOS
    // Verifica se a variável global CHART_DATA existe (vinda do PHP)
    if (typeof CHART_DATA !== 'undefined' && CHART_DATA !== null) {
        console.log("Dados recebidos do PHP:", CHART_DATA); // DEBUG
        renderizarGraficos(CHART_DATA);
    } else {
        console.error("Erro: Variável CHART_DATA não encontrada. Verifique se o PHP está injetando o JSON corretamente.");
    }
});

/**
 * FUNÇÃO AUXILIAR: Converte qualquer formato numérico (string com vírgula ou ponto) em número real
 */
function limparNumero(valor) {
    if (typeof valor === 'number') return valor;
    if (!valor) return 0;
    // Remove pontos de milhar e troca vírgula decimal por ponto
    let limpo = valor.toString().replace(/\./g, '').replace(',', '.');
    return parseFloat(limpo) || 0;
}

/**
 * RENDERIZAÇÃO DOS GRÁFICOS (CHART.JS)
 */
function renderizarGraficos(data) {
    // 1. Processamento de dados de Segurança (Garante que são números)
    const receitas = data.receitas.map(v => limparNumero(v));
    const despesas = data.despesas.map(v => Math.abs(limparNumero(v))); // Garante despesa positiva para o cálculo
    
    // CÁLCULO DO LUCRO REAL: RECEITA - DESPESA
    const lucros = receitas.map((rec, i) => rec - despesas[i]);

    console.log("Calculado - Receitas:", receitas);
    console.log("Calculado - Despesas:", despesas);
    console.log("Calculado - Lucros:", lucros);

    // Configurações comuns
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    label: (ctx) => `${ctx.dataset.label}: ${new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(ctx.parsed.y)}`
                }
            }
        }
    };

    // --- GRÁFICO DE LINHA ---
    const ctxLinha = document.getElementById('chartLinha');
    if (ctxLinha) {
        // Destruir gráfico existente para evitar bugs de sobreposição
        const existingChart = Chart.getChart("chartLinha");
        if (existingChart) existingChart.destroy();

        new Chart(ctxLinha, {
            type: 'line',
            data: {
                labels: data.labels || [],
                datasets: [
                    {
                        label: 'Receitas',
                        data: receitas,
                        borderColor: '#2dd4bf',
                        backgroundColor: 'rgba(45, 212, 191, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Despesas',
                        data: despesas,
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244, 63, 94, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Lucro (Saldo)',
                        data: lucros,
                        borderColor: '#3b82f6',
                        borderWidth: 4,
                        fill: false,
                        tension: 0.4,
                        pointRadius: 5
                    }
                ]
            },
            options: {
                ...commonOptions,
                scales: {
                    y: {
                        beginAtZero: false, // Importante para mostrar lucro negativo (prejuízo)
                        ticks: {
                            callback: (val) => val.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
                        }
                    }
                }
            }
        });
    }

    // --- GRÁFICO DE ROSCA (Categorias) ---
    const ctxPizza = document.getElementById('chartPizza');
    if (ctxPizza) {
        const existingPizza = Chart.getChart("chartPizza");
        if (existingPizza) existingPizza.destroy();

        const catValores = (data.catValores || []).map(v => Math.abs(limparNumero(v)));

        new Chart(ctxPizza, {
            type: 'doughnut',
            data: {
                labels: data.catLabels || [],
                datasets: [{
                    data: catValores,
                    backgroundColor: ['#1e293b', '#2dd4bf', '#3b82f6', '#8b5cf6', '#f43f5e', '#facc15'],
                    borderWidth: 2
                }]
            },
            options: {
                ...commonOptions,
                cutout: '70%'
            }
        });
    }
}

/**
 * LÓGICA DE EXPORTAÇÃO
 */
window.gerarRelatorio = function() {
    const formato = document.querySelector('input[name="formato"]:checked')?.value || 'pdf';
    const tipo = document.getElementById('export_tipo')?.value || 'ambos';
    const mes = document.getElementById('export_mes')?.value;
    const ano = document.getElementById('export_ano')?.value;

    const url = `gerar_relatorio.php?formato=${formato}&tipo=${tipo}&mes=${mes}&ano=${ano}`;
    window.location.href = url;
};