<?php


// 1. Configurações de erro para desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Definição do caminho do banco de dados JSON
// Usamos __DIR__ para garantir que o PHP encontre o arquivo independente de quem inclua este script
$caminho_json = __DIR__ . '/../Dashboard/banco.json';

// 3. Estrutura padrão (Caso o arquivo não exista ou esteja vazio)
$default_storage = [
    'saldo_total' => 0, 
    'receitas_mes' => 0, 
    'despesas_mes' => 0, 
    'transacoes' => []
];

// 4. Carregamento e decodificação dos dados
if (file_exists($caminho_json)) {
    $conteudo = file_get_contents($caminho_json);
    $storage = json_decode($conteudo, true);
    
    // Validação se o JSON é válido
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($storage)) {
        $storage = $default_storage;
    }
} else {
    $storage = $default_storage;
}

// 5. Preparação das variáveis globais
// Forçamos o cast para (array) para evitar erros de "invalid foreach" no index.php
$transacoes = (array)($storage['transacoes'] ?? []);

$dados_financeiros = [
    'receitas_mes' => $storage['receitas_mes'] ?? 0,
    'despesas_mes' => $storage['despesas_mes'] ?? 0,
    'saldo_total'  => $storage['saldo_total'] ?? 0
];

/**
 * Função Auxiliar: Formata valores numéricos para o padrão de moeda Real (R$)
 */
if (!function_exists('formatarMoeda')) {
    function formatarMoeda($valor) {
        // Converte para float caso venha como string do JSON
        $valorFloat = (float)$valor;
        return 'R$ ' . number_format(abs($valorFloat), 2, ',', '.');
    }
}

// 6. Pequeno ajuste de segurança para o index.php
// Se o array de transações estiver mal estruturado internamente, 
// este filtro limpa entradas nulas
$transacoes = array_filter($transacoes, function($item) {
    return is_array($item) && isset($item['titulo'], $item['valor']);
});
?>