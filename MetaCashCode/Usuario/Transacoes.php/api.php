<?php
// api.php
$data = [
    "resumo" => [
        "receitas" => "95.500",
        "despesas" => "66.700",
        "saldo"    => "28.800"
    ],
    "transacoes" => [
        ["nome" => "Venda Cliente XYZ", "cat" => "Salário", "data" => "01/03/2026", "valor" => 45000.00, "tipo" => "entrada"],
        ["nome" => "Fornecedor ABC - Matéria Prima", "cat" => "Compras", "data" => "03/03/2026", "valor" => 15000.00, "tipo" => "saida"],
        ["nome" => "Aluguel Escritório", "cat" => "Moradia", "data" => "05/03/2026", "valor" => 8500.00, "tipo" => "saida"],
        ["nome" => "Pagamento Fornecedor DEF", "cat" => "Compras", "data" => "07/03/2026", "valor" => 12000.00, "tipo" => "saida"],
        ["nome" => "Venda Cliente LMN", "cat" => "Salário", "data" => "08/03/2026", "valor" => 32000.00, "tipo" => "entrada"],
        ["nome" => "Folha de Pagamento", "cat" => "Alimentação", "data" => "10/03/2026", "valor" => 28000.00, "tipo" => "saida"],
        ["nome" => "Venda Cliente OPQ", "cat" => "Salário", "data" => "12/03/2026", "valor" => 18500.00, "tipo" => "entrada"],
        ["nome" => "Manutenção Equipamentos", "cat" => "Transporte", "data" => "15/03/2026", "valor" => 3200.00, "tipo" => "saida"]
    ]
];