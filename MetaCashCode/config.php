<?php
// Carrega as variáveis de ambiente do arquivo .env
$url = getenv('DATABASE_URL');

// Se a URL não estiver definida, encerra a execução com erro.
if ($url === false) {
    die("Erro: A variável de ambiente DATABASE_URL não está definida. Verifique seu arquivo .env.");
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
    die("Erro: Nao foi possivel analisar a DATABASE_URL. Verifique o formato no arquivo .env.");
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
    // Em caso de erro na conexão, exibe a mensagem e encerra
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>