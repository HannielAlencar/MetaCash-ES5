<?php
// Configurações do MySQL
$host     = '127.0.0.1';
$port     = '3306'; // Porta padrão do MySQL (geralmente 3306)
$db_name  = 'MetaC';
$usuario  = 'root';   // Usuário padrão do MySQL (comum ser 'root')
$senha    = '0936';   // Sua senha do banco de dados

try {
    // A string de conexão agora utiliza o driver 'mysql'
    // Note que para MySQL usamos 'dbname' (sem o underline que alguns drivers aceitam)
    $dsn = "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4";
    
    $pdo = new PDO($dsn, $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false, // Melhora a segurança e performance
    ]);

    // Opcional: ecoar sucesso para teste inicial
    // echo "Conectado ao MySQL com sucesso!";

} catch (PDOException $e) {
    // Em produção, evite mostrar o $e->getMessage() diretamente ao usuário final
    die("Erro na conexão com MySQL: " . $e->getMessage());
}
?>