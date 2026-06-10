<?php
// api.php
$arquivo_db = __DIR__ . '/../../Usuario/Dashboard/banco.json';

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

if (is_file($arquivo_db) && is_readable($arquivo_db)) {
    $json = file_get_contents($arquivo_db);
    $storage = json_decode($json, true);

    if (is_array($storage)) {
        $data["transacoes"] = $storage["transacoes"] ?? $data["transacoes"];
        $data["resumo"] = [
            "receitas" => isset($storage["receitas_mes"]) ? (string)$storage["receitas_mes"] : $data["resumo"]["receitas"],
            "despesas" => isset($storage["despesas_mes"]) ? (string)$storage["despesas_mes"] : $data["resumo"]["despesas"],
            "saldo"    => isset($storage["saldo_total"]) ? (string)$storage["saldo_total"] : $data["resumo"]["saldo"]
        ];
    }
}
