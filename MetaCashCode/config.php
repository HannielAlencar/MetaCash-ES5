<?php
// Carrega as variáveis de ambiente do arquivo .env
$url = getenv('DATABASE_URL');

// Se a URL não estiver definida, encerra a execução com erro.
if ($url === false) {
    die("Erro: A variável de ambiente DATABASE_URL não está definida.");
}

// Analisa a URL do banco de dados
$db_parts = parse_url($url);

// Extrai as credenciais e detalhes da conexão
$host = $db_parts['host'] ?? null;
$port = $db_parts['port'] ?? '5432';
$user = $db_parts['user'] ?? null;
$pass = $db_parts['pass'] ?? null;
$dbname = isset($db_parts['path']) ? ltrim($db_parts['path'], '/') : null;

if (!$host || !$user || !$pass || !$dbname) {
    die("Erro: Nao foi possivel analisar a DATABASE_URL.");
}

// Monta a string de conexão (DSN) para o PostgreSQL
$dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";

// Adiciona sslmode=require se estiver presente nos parâmetros da query da URL
if (isset($db_parts['query'])) {
    parse_str($db_parts['query'], $query_params);
    if (isset($query_params['sslmode'])) {
        $dsn .= ";sslmode=" . $query_params['sslmode'];
    }
}

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Cria a instância do PDO para a conexão
    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("SET TIME ZONE 'America/Sao_Paulo'");
    date_default_timezone_set('America/Sao_Paulo');
} catch (PDOException $e) {
    // Em caso de erro, loga no servidor mas não gera saída visual desnecessária se não for fatal
    error_log("Erro na conexão com o banco de dados: " . $e->getMessage());
    die("Erro interno de conexão.");
}

// ... após session_start e require_once config.php ...

$id_empresa = $_SESSION['id_empresa'] ?? 0;

// Valores padrão (para não dar erro caso não tenha nada no banco ainda)
$cfg = [
    'titulo_pagina' => 'Transações',
    'subtitulo_pagina' => 'Gerencie todas as receitas e despesas da empresa',
    'texto_botao' => 'Nova Transação',
    'placeholder_busca' => 'Buscar transações...',
    'mensagem_vazio' => 'Nenhuma transação encontrada',
    'fonte_pagina' => 'Inter',
    'tamanho_fonte' => 'medio',
    'vis_receitas' => '1',
    'vis_despesas' => '1',
    'vis_saldo' => '1',
    'vis_lista' => '1'
];

// Busca do banco
$stmt = $pdo->prepare("SELECT chave_config, valor_config FROM configs_paginas WHERE id_empresa = :empresa");
$stmt->execute([':empresa' => $id_empresa]);
$configs_banco = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Mescla o que veio do banco com os padrões
$cfg = array_merge($cfg, $configs_banco);

// Mapeamento de tamanho de fonte para Tailwind
$tamanho_map = [
    'pequeno' => 'text-sm',
    'medio'   => 'text-base',
    'grande'  => 'text-lg',
    'extra'   => 'text-xl'
];
$classe_tamanho = $tamanho_map[$cfg['tamanho_fonte']] ?? 'text-base';

/**
 * Função de registro de histórico
 */
function registrarHistorico($pdo, $usuario_id, $acao, $categoria, $descricao) {
    try {
        $sql = "INSERT INTO historico (usuario_id, acao, categoria, descricao) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario_id, $acao, $categoria, $descricao]);
        return true;
    } catch (PDOException $e) {
        error_log("Erro ao gravar histórico: " . $e->getMessage());
        return false;
    }
}
// NÃO ADICIONE A TAG DE FECHAMENTO AQUI (evita erros de header)