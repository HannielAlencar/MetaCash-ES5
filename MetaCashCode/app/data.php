<?php

require_once __DIR__ . '/../config.php';
// 1. SEGURANÇA E CONEXÃO
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_empresa'])) {
    // Se não tiver empresa logada, não carrega dados
    $id_empresa = 0; 
} else {
    $id_empresa = $_SESSION['id_empresa'];
}

// BUSCA TOTAIS DO BANCO DE DADOS (MySQL)
$total_receitas = 0;
$total_despesas = 0;
$saldo_total = 0;

try {
    $stmt_totais = $pdo->prepare("SELECT 
        SUM(CASE WHEN tipo_transacao = 'Receita' THEN valor_transacao ELSE 0 END) as receitas,
        SUM(CASE WHEN tipo_transacao = 'Despesa' THEN valor_transacao ELSE 0 END) as despesas
        FROM transacoes WHERE id_empresa = :empresa");
    $stmt_totais->execute([':empresa' => $id_empresa]);
    $resultado_totais = $stmt_totais->fetch();

    if ($resultado_totais) {
        $total_receitas = (float)$resultado_totais['receitas'];
        $total_despesas = (float)$resultado_totais['despesas'];
        $saldo_total = $total_receitas - $total_despesas;
    }
} catch (PDOException $e) {
    error_log("Erro ao buscar totais no data.php: " . $e->getMessage());
}

// LÓGICA PARA O GRÁFICO DE LINHA (Simplificado para o momento atual)
$labels_meses = [date('M')]; // Mostra o mês atual (ex: 'May')
$dados_receitas = [$total_receitas]; 
$dados_despesas = [$total_despesas]; 
$dados_lucro = [$saldo_total];

// 4. LÓGICA PARA O GRÁFICO DE PIZZA/ROSCA (Distribuição por Categorias)
$categorias_temp = [];

try {
    // Puxa a soma de valores agrupados pelo nome da categoria
    $stmt_cat = $pdo->prepare("
        SELECT c.nome_categoria, t.tipo_transacao, SUM(t.valor_transacao) as total
        FROM transacoes t
        LEFT JOIN categoria c ON t.id_categoria = c.id_categoria
        WHERE t.id_empresa = :empresa
        GROUP BY c.nome_categoria, t.tipo_transacao
    ");
    $stmt_cat->execute([':empresa' => $id_empresa]);
    $categorias_db = $stmt_cat->fetchAll();

    // Processa Receitas vs Despesas para o gráfico
    foreach ($categorias_db as $row) {
        $cat = $row['nome_categoria'] ?? 'Geral';
        $valor = (float)$row['total'];
        
        if ($row['tipo_transacao'] === 'Despesa') {
            $categorias_temp[$cat] = ($categorias_temp[$cat] ?? 0) - $valor;
        } else {
            $categorias_temp[$cat] = ($categorias_temp[$cat] ?? 0) + $valor;
        }
    }
} catch (PDOException $e) {
    error_log("Erro no gráfico de categorias: " . $e->getMessage());
}

// Se o banco estiver vazio, coloca um dado falso só para o gráfico não dar erro visual
if (empty($categorias_temp)) {
    $categorias_temp['Sem movimentação'] = 0;
}

// PREPARAÇÃO PARA O JAVASCRIPT DOS GRÁFICOS
$categorias_valores = array_values($categorias_temp);
$categorias_labels = array_keys($categorias_temp);

// 5. CARDS DO DASHBOARD
$lucro_mes = $total_receitas - $total_despesas; 

$cards = [
    'Lucro Mensal' => [
        'valor' => ($lucro_mes > 0 ? '+' : '') . number_format($lucro_mes, 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => $lucro_mes >= 0 ? 'text-teal-500' : 'text-rose-500'
    ],
    'Total de Receitas' => [
        'valor' => '+' . number_format($total_receitas, 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => 'text-teal-500'
    ],
    'Total de Despesas' => [
        'valor' => '-' . number_format($total_despesas, 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => 'text-rose-500'
    ],
    'Saldo Total' => [
        'valor' => ($saldo_total > 0 ? '+' : '') . number_format($saldo_total, 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => $saldo_total >= 0 ? 'text-blue-500' : 'text-rose-500'
    ],
];
?>
