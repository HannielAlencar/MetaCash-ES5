<?php
// data.php

// 1. Função para formatar moeda brasileira
if (!function_exists('formatarMoeda')) {
    function formatarMoeda($valor) {
        return "R$ " . number_format($valor, 2, ',', '.');
    }
}

// 2. Variável principal que o index.php está pedindo
$dados = [
    "saldo_total"  => 285750.50,
    "receitas_mes" => 77000.00,
    "despesas_mes" => 72500.00
];

// 3. Dados dos Mini Cards
$cards = [
    "faturamento" => ["valor" => "95.5k", "porcentagem" => "+12.5%", "cor" => "text-teal-500"],
    "entradas"    => ["valor" => "3", "porcentagem" => "+8.2%", "cor" => "text-teal-500"],
    "saidas"      => ["valor" => "5", "porcentagem" => "-5.3%", "cor" => "text-rose-500"],
    "saldo"       => ["valor" => "28.8k", "porcentagem" => "+15.8%", "cor" => "text-teal-500"]
];

// 4. Dados para o Gráfico de Linha (Receitas vs Despesas)
$labels_meses = ["Set", "Out", "Nov", "Dez", "Jan", "Fev", "Mar"];
$dados_receitas = [85000, 98000, 80000, 125000, 90000, 110000, 85000];
$dados_despesas = [70000, 72000, 68000, 85000, 75000, 70000, 72000];

// 5. Dados para o Gráfico de Pizza (Categorias)
$categorias = [
    "Salário" => 37,
    "Administrativo" => 35,
    "Compras" => 12,
    "Marketing" => 7,
    "Manutenção" => 5
];

// 6. Transações Recentes
$transacoes = [
    ["titulo" => "Venda Cliente XYZ", "cat" => "Salário", "data" => "01/03/2026", "valor" => 45000, "tipo" => "e"],
    ["titulo" => "Fornecedor ABC", "cat" => "Compras", "data" => "03/03/2026", "valor" => 15000, "tipo" => "s"],
    ["titulo" => "Aluguel Escritório", "cat" => "Moradia", "data" => "05/03/2026", "valor" => 8500, "tipo" => "s"],
    ["titulo" => "Pagamento Fornecedor DEF", "cat" => "Compras", "data" => "07/03/2026", "valor" => 12000, "tipo" => "s"],
    ["titulo" => "Venda Cliente LMN", "cat" => "Salário", "data" => "08/03/2026", "valor" => 32000, "tipo" => "e"],
    ["titulo" => "Folha de Pagamento", "cat" => "Alimentação", "data" => "10/03/2026", "valor" => 28000, "tipo" => "s"],
];