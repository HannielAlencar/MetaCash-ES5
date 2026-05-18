<?php
require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    //  Pega os dados do formulário
    $titulo = $_POST['titulo'] ?? 'Sem título';
    $valor  = (float)($_POST['valor'] ?? 0);
    $tipo   = $_POST['tipo'] ?? 'e';
    $cat    = $_POST['cat'] ?? 'Geral';
    $origem = $_POST['origem'] ?? 'dashboard';

    // Traduz para o formato que o seu Banco de Dados exige
    $tipo_banco = ($tipo === 'e') ? 'Receita' : 'Despesa';
    $data_transacao = date('Y-m-d'); // data no formato Ano-Mês-Dia
    $data_registro = date('Y-m-d H:i:s'); // data e hora no formato Ano-Mês-Dia Hora:Min:Seg

    $id_empresa = $_SESSION['id_empresa']; 
    $id_usuario = $_SESSION['id_usuario'];

    try {
        // Normaliza a categoria escolhida
        $cat = trim($cat);
        if ($cat === '') {
            $cat = 'Geral';
        }

        // Descobre o ID da Categoria baseada no nome ('Vendas', 'Geral', etc)
        $stmt_cat = $pdo->prepare("SELECT id_categoria FROM categoria WHERE nome_categoria = :nome AND id_empresa = :empresa LIMIT 1");
        $stmt_cat->execute([':nome' => $cat, ':empresa' => $id_empresa]);
        $categoria_db = $stmt_cat->fetch();

        // Se não existir, cria a categoria para a empresa
        if (!$categoria_db) {
            $stmt_nova = $pdo->prepare("INSERT INTO categoria (id_empresa, nome_categoria, tipo_categoria) VALUES (:empresa, :nome, :tipo)");
            $stmt_nova->execute([
                ':empresa' => $id_empresa,
                ':nome'    => $cat,
                ':tipo'    => $tipo_banco
            ]);
            $id_categoria = $pdo->lastInsertId();
        } else {
            $id_categoria = $categoria_db['id_categoria'];
        }

        // 4. Salva no Banco de Dados (MySQL)
        $sql = "INSERT INTO transacoes (id_empresa, id_usuario, id_categoria, tipo_transacao, valor_transacao, data_transacao, data_registro, descricao_transacao) 
                VALUES (:empresa, :usuario, :cat, :tipo, :valor, :data_transacao, :data_registro, :descricao)";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':empresa'   => $id_empresa,
            ':usuario'   => $id_usuario,
            ':cat'       => $id_categoria,
            ':tipo'      => $tipo_banco,
            ':valor'     => $valor,
            ':data_transacao' => $data_transacao,
            ':data_registro'  => $data_registro,
            ':descricao' => $titulo
        ]);

        // 5. Redirecionamento
if ($origem === 'transacoes') {
            header("Location: ../app/transacoes.php");
        } else {
            header("Location: ../app/dashboardUsuario.php"); 
        }
        exit();
    } catch (PDOException $e) {
        // Se der erro de chave estrangeira ou conexão, ele avisa na tela
        die("Erro ao salvar no banco de dados: " . $e->getMessage());
    }
}
?>
