<?php
<<<<<<< Updated upstream
=======
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
>>>>>>> Stashed changes
include '../../../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Pega os dados do formulário
    $titulo = $_POST['titulo'] ?? 'Sem título';
    $valor  = (float)($_POST['valor'] ?? 0);
    $tipo   = $_POST['tipo'] ?? 'e';
    $cat    = $_POST['cat'] ?? 'Geral';
    $origem = $_POST['origem'] ?? 'dashboard';

    // 2. Traduz para o formato que o seu Banco de Dados exige
    $tipo_banco = ($tipo === 'e') ? 'Receita' : 'Despesa';
    $data_atual = date('Y-m-d'); // data no formato Ano-Mês-Dia

    $id_empresa = $_SESSION['id_empresa']; 
    $id_usuario = $_SESSION['id_usuario'];

    try {
        // 3. Descobre o ID da Categoria baseada no nome ('Vendas', 'Geral', etc)
        $stmt_cat = $pdo->prepare("SELECT id_categoria FROM categoria WHERE nome_categoria = :nome AND id_empresa = :empresa LIMIT 1");
        $stmt_cat->execute([':nome' => $cat, ':empresa' => $id_empresa]);
        $categoria_db = $stmt_cat->fetch();
        
        // Se achar a categoria no banco, usa o ID dela. Se não achar, usa o ID 1 por padrão.
        $id_categoria = $categoria_db ? $categoria_db['id_categoria'] : 1; 

        // 4. Salva no Banco de Dados (MySQL)
        $sql = "INSERT INTO transacoes (id_empresa, id_usuario, id_categoria, tipo_transacao, valor_transacao, data_transacao, descricao_transacao) 
                VALUES (:empresa, :usuario, :cat, :tipo, :valor, :data, :descricao)";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':empresa'   => $id_empresa,
            ':usuario'   => $id_usuario,
            ':cat'       => $id_categoria,
            ':tipo'      => $tipo_banco,
            ':valor'     => $valor,
            ':data'      => $data_atual,
            ':descricao' => $titulo
        ]);

        // 5. Redirecionamento
if ($origem === 'transacoes') {
            header("Location: index.php");
        } else {
            header("Location: index.php"); 
        }
        exit();
    } catch (PDOException $e) {
        // Se der erro de chave estrangeira ou conexão, ele avisa na tela
        die("Erro ao salvar no banco de dados: " . $e->getMessage());
    }
}
?>