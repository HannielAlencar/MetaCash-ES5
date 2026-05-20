<?php
$host = "localhost";
$dbname = "metacash-db";
$user = "root"; // No MySQL, o usuário padrão geralmente é 'root'
$pass = "";     // No MySQL local (XAMPP/WAMP), a senha geralmente é vazia

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Mudança na DSN: de 'pgsql:' para 'mysql:'
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        
        // Configura o PDO para lançar exceções em caso de erro
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO empresas (nome_empresa, nome_responsavel, email, telefone, cnpj, cpf, senha) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['nome_empresa'], 
            $_POST['nome_responsavel'], 
            $_POST['email'], 
            $_POST['telefone'], 
            $_POST['cnpj'], 
            $_POST['cpf'], 
            $senha_hash
        ]);
        
        // Redireciona com sucesso
        header("Location: index.php?sucesso=1");
        exit(); // Boa prática interromper o script após um header redirect
        
    } catch (PDOException $e) {
        // Redireciona com erro
        header("Location: index.php?erro=" . urlencode($e->getMessage()));
        exit();
    }
}