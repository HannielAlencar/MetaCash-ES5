<?php
// Simulação de consulta ao banco de dados
$empresa = "Empresa LTDA";
$email_empresa = "empresa@exemplo.com";

$relatorios_recentes = [
    ['nome' => 'Demonstrativo de Resultados', 'data' => '11/03/2026', 'tipo' => 'DRE'],
    ['nome' => 'Fluxo de Caixa', 'data' => '28/02/2026', 'tipo' => 'Fluxo'],
    ['nome' => 'Balanço Patrimonial', 'data' => '31/01/2026', 'tipo' => 'Balanço'],
    ['nome' => 'Análise de Despesas', 'data' => '31/01/2026', 'tipo' => 'Análise']
];

// Dados para os gráficos (JS consumirá isso)
$labels_meses = ['Set', 'Out', 'Nov', 'Dez', 'Jan', 'Fev', 'Mar'];
$dados_receita = [95000, 105000, 98000, 125000, 88000, 108000, 85000];
$dados_despesa = [70000, 75000, 70000, 88000, 68000, 75000, 75000];