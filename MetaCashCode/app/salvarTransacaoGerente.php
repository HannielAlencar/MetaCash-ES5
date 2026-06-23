<?php
// 1. PRIMEIRO o config (para herdar qualquer configuração de sessão do seu sistema)
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2. BLINDAGEM DUPLA: Tenta pegar da sessão. Se a sessão falhar, pega do formulário oculto!
    $id_empresa = (int)($_SESSION['id_empresa'] ?? $_POST['id_empresa_oculto'] ?? 0);
    $id_usuario = (int)($_SESSION['id_usuario'] ?? $_POST['id_usuario_oculto'] ?? 0);

    // Se continuar 0, o sistema avisa na hora em vez de salvar fantasma
    if ($id_empresa === 0 || $id_usuario === 0) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro de identificação: Empresa ou Usuário não encontrados.']);
        exit();
    }

    $titulo = trim($_POST['titulo'] ?? 'Sem titulo');
    $valor = (float)($_POST['valor'] ?? 0);
    $tipo_raw = $_POST['tipo'] ?? 'e';
    $cat = trim($_POST['cat'] ?? 'Geral');
    $data_raw = trim($_POST['data'] ?? '');

    $tipo = ($tipo_raw === 's' || $tipo_raw === 'Despesa') ? 'Despesa' : 'Receita';

    $data_obj = DateTime::createFromFormat('Y-m-d', $data_raw);
    if (!$data_obj) {
        $data_obj = new DateTime();
    }
    $data_transacao = $data_obj->format('Y-m-d');

    if ($valor <= 0) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Valor inválido']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        $sql_categoria = "SELECT id_categoria FROM categoria WHERE id_empresa = :empresa AND nome_categoria = :nome AND tipo_categoria = :tipo LIMIT 1";
        $stmt_categoria = $pdo->prepare($sql_categoria);
        $stmt_categoria->execute([
            ':empresa' => $id_empresa,
            ':nome' => $cat,
            ':tipo' => $tipo
        ]);
        $categoria = $stmt_categoria->fetch();

        if ($categoria) {
            $id_categoria = (int)$categoria['id_categoria'];
        } else {
            $sql_nova_categoria = "INSERT INTO categoria (id_empresa, nome_categoria, tipo_categoria) VALUES (:empresa, :nome, :tipo)";
            $stmt_nova_categoria = $pdo->prepare($sql_nova_categoria);
            $stmt_nova_categoria->execute([
                ':empresa' => $id_empresa,
                ':nome' => $cat,
                ':tipo' => $tipo
            ]);
            
            $id_categoria = $pdo->lastInsertId();
            
            if (!$id_categoria) {
                $stmt_busca = $pdo->prepare("SELECT id_categoria FROM categoria WHERE id_empresa = :empresa AND nome_categoria = :nome ORDER BY id_categoria DESC LIMIT 1");
                $stmt_busca->execute([':empresa' => $id_empresa, ':nome' => $cat]);
                $id_categoria = (int)$stmt_busca->fetchColumn();
            }
        }

        if (!$id_categoria || $id_categoria <= 0) {
            throw new Exception("Falha ao gerar ou recuperar o ID da categoria.");
        }

        $sql_transacao = "INSERT INTO transacoes (id_empresa, id_usuario, id_categoria, tipo_transacao, valor_transacao, data_transacao, descricao_transacao) VALUES (:empresa, :usuario, :categoria, :tipo, :valor, :data, :descricao)";
        $stmt_transacao = $pdo->prepare($sql_transacao);
        $stmt_transacao->execute([
            ':empresa' => $id_empresa,
            ':usuario' => $id_usuario,
            ':categoria' => $id_categoria,
            ':tipo' => $tipo,
            ':valor' => $valor,
            ':data' => $data_transacao,
            ':descricao' => $titulo
        ]);
        
       // ... logo após o $stmt_transacao->execute ...

        // --- HISTÓRICO ISOLADO ---
        // --- INSERÇÃO CORRETA E AJUSTADA AO SEU POSTGRESQL ---
        try {
            $sql_log = "INSERT INTO historico (usuario_id, acao, categoria, descricao, data_criacao) 
                        VALUES (:usuario, :acao, :categoria, :desc, CURRENT_TIMESTAMP)";
            
            $stmt_log = $pdo->prepare($sql_log);
            $stmt_log->execute([
                ':usuario'   => $id_usuario,
                ':acao'      => 'Criação',
                ':categoria' => $tipo,
                ':desc'      => "Nova transação de {$tipo} criada: '{$titulo}' no valor de R$ " . number_format($valor, 2, ',', '.')
            ]);
        } catch (Throwable $e_log) {
            // Se falhar aqui, o sistema registra no log e continua o commit da transação principal
            error_log("Erro no histórico: " . $e_log->getMessage());
        }

        $pdo->commit();

        echo json_encode(['status' => 'sucesso', 'mensagem' => 'Transação salva com sucesso']);
        exit();

    } catch (Throwable $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro no Banco: ' . $e->getMessage()]);
        exit();
    }
}

echo json_encode(['status' => 'erro', 'mensagem' => 'Método não permitido']);
?>