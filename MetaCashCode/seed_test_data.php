<?php
/**
 * SEED - POPULA BANCO COM DADOS DE TESTE
 * Execute uma vez para preparar dados realistas para testes
 * 
 * Use: http://localhost/seed_test_data.php
 * Ou: php seed_test_data.php
 */

require_once __DIR__ . '/config.php';

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Iniciando população de dados de teste...\n";

    // 1. INSERIR EMPRESA DE TESTE
    $sql_empresa = "INSERT INTO empresas (nome_empresa, cnpj) VALUES (:nome, :cnpj) ON CONFLICT DO NOTHING";
    $stmt = $pdo->prepare($sql_empresa);
    $stmt->execute([
        ':nome' => 'TechCorp Brasil',
        ':cnpj' => '12345678000190'
    ]);

    // Busca ID da empresa
    $stmt = $pdo->prepare("SELECT id_empresa FROM empresas WHERE cnpj = :cnpj LIMIT 1");
    $stmt->execute([':cnpj' => '12345678000190']);
    $empresa = $stmt->fetch();
    $id_empresa = $empresa['id_empresa'] ?? 1;

    echo "✓ Empresa criada/encontrada: ID {$id_empresa}\n";

    // 2. INSERIR USUÁRIOS DE TESTE
    $usuarios = [
        [
            'nome' => 'Carlos Manager',
            'email' => 'carlos@techcorp.com',
            'cpf' => '12345678901',
            'matricula' => 'GERENTE001',
            'nivel' => 'Gerente'
        ],
        [
            'nome' => 'Ana Silva',
            'email' => 'ana@techcorp.com',
            'cpf' => '98765432101',
            'matricula' => 'USER001',
            'nivel' => 'Membro'
        ],
        [
            'nome' => 'Bruno Santos',
            'email' => 'bruno@techcorp.com',
            'cpf' => '55544433322',
            'matricula' => 'USER002',
            'nivel' => 'Membro'
        ],
        [
            'nome' => 'Marina Costa',
            'email' => 'marina@techcorp.com',
            'cpf' => '11122233344',
            'matricula' => 'USER003',
            'nivel' => 'Membro'
        ]
    ];

    $ids_usuarios = [];

    foreach ($usuarios as $user) {
        $cpf_clean = preg_replace('/\D/', '', $user['cpf']);
        $sql_user = "INSERT INTO usuarios (id_empresa, nome_completo, email, cpf, matricula, senha, nivel_permissao) 
                    VALUES (:empresa, :nome, :email, :cpf, :matricula, :senha, :nivel)
                    ON CONFLICT (cpf) DO NOTHING";
        $stmt = $pdo->prepare($sql_user);
        $stmt->execute([
            ':empresa' => $id_empresa,
            ':nome' => $user['nome'],
            ':email' => $user['email'],
            ':cpf' => $cpf_clean,
            ':matricula' => $user['matricula'],
            ':senha' => password_hash('Senha@123', PASSWORD_DEFAULT),
            ':nivel' => $user['nivel']
        ]);

        // Recupera ID do usuário
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE cpf = :cpf LIMIT 1");
        $stmt->execute([':cpf' => $cpf_clean]);
        $u = $stmt->fetch();
        if ($u) {
            $ids_usuarios[] = $u['id_usuario'];
            echo "✓ Usuário criado: {$user['nome']} (ID {$u['id_usuario']})\n";
        }
    }

    // 3. INSERIR CATEGORIAS
    $categorias = ['Vendas', 'Salários', 'Aluguel', 'Fornecedores', 'Outros'];
    $ids_categorias = [];

    foreach ($categorias as $cat) {
        $sql_cat = "INSERT INTO categorias (id_empresa, nome_categoria) 
                   VALUES (:empresa, :nome)
                   ON CONFLICT DO NOTHING";
        $stmt = $pdo->prepare($sql_cat);
        $stmt->execute([':empresa' => $id_empresa, ':nome' => $cat]);

        $stmt = $pdo->prepare("SELECT id_categoria FROM categorias WHERE nome_categoria = :nome AND id_empresa = :empresa LIMIT 1");
        $stmt->execute([':nome' => $cat, ':empresa' => $id_empresa]);
        $c = $stmt->fetch();
        if ($c) {
            $ids_categorias[$cat] = $c['id_categoria'];
        }
    }

    echo "✓ " . count($ids_categorias) . " categorias criadas\n";

    // 4. INSERIR TRANSAÇÕES DE TESTE
    $transacoes = [
        ['tipo' => 'Receita', 'categoria' => 'Vendas', 'valor' => 5000.00, 'descricao' => 'Venda de serviços - Projeto A'],
        ['tipo' => 'Receita', 'categoria' => 'Vendas', 'valor' => 3500.00, 'descricao' => 'Venda de serviços - Projeto B'],
        ['tipo' => 'Despesa', 'categoria' => 'Salários', 'valor' => 8000.00, 'descricao' => 'Folha de pagamento Junho'],
        ['tipo' => 'Despesa', 'categoria' => 'Aluguel', 'valor' => 2500.00, 'descricao' => 'Aluguel escritório'],
        ['tipo' => 'Despesa', 'categoria' => 'Fornecedores', 'valor' => 1200.00, 'descricao' => 'Compra de materiais'],
        ['tipo' => 'Receita', 'categoria' => 'Vendas', 'valor' => 2000.00, 'descricao' => 'Consultoria técnica'],
        ['tipo' => 'Despesa', 'categoria' => 'Outros', 'valor' => 300.00, 'descricao' => 'Material de escritório'],
        ['tipo' => 'Despesa', 'categoria' => 'Fornecedores', 'valor' => 4500.00, 'descricao' => 'Compra de equipamentos'],
    ];

    $contador_transacoes = 0;
    foreach ($transacoes as $trans) {
        $id_usuario_trans = $ids_usuarios[array_rand($ids_usuarios)];
        $id_categoria = $ids_categorias[$trans['categoria']] ?? 1;
        $data = date('Y-m-d H:i:s', strtotime("-" . rand(0, 30) . " days"));

        $sql_trans = "INSERT INTO transacoes (id_empresa, id_usuario, id_categoria, tipo_transacao, valor_transacao, descricao, data_transacao) 
                     VALUES (:empresa, :usuario, :categoria, :tipo, :valor, :desc, :data)";
        $stmt = $pdo->prepare($sql_trans);
        $stmt->execute([
            ':empresa' => $id_empresa,
            ':usuario' => $id_usuario_trans,
            ':categoria' => $id_categoria,
            ':tipo' => $trans['tipo'],
            ':valor' => $trans['valor'],
            ':desc' => $trans['descricao'],
            ':data' => $data
        ]);
        $contador_transacoes++;
    }

    echo "✓ {$contador_transacoes} transações criadas\n";

    echo "\n" . str_repeat("=", 60) . "\n";
    echo "DADOS DE TESTE CRIADOS COM SUCESSO!\n";
    echo str_repeat("=", 60) . "\n";
    echo "Empresa: TechCorp Brasil (ID: {$id_empresa})\n";
    echo "Usuários criados: " . count($ids_usuarios) . "\n";
    echo "\nCREDENCIAIS DE TESTE:\n";
    echo "  - Email: carlos@techcorp.com (Gerente)\n";
    echo "  - Email: ana@techcorp.com (Usuário)\n";
    echo "  - Senha: Senha@123\n";
    echo "\nAgora você pode usar test_login.php para testar as páginas!\n";
    echo str_repeat("=", 60) . "\n";

} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'UNIQUE constraint failed') || strpos($e->getMessage(), 'duplicate key') || strpos($e->getMessage(), 'already exists')) {
        echo "⚠ Aviso: Alguns dados já existem no banco (duplicados ignorados)\n";
        echo "Se quiser limpar e recomeçar, execute:\n";
        echo "  DELETE FROM transacoes;\n";
        echo "  DELETE FROM usuarios;\n";
        echo "  DELETE FROM empresas;\n";
        echo "  DELETE FROM categorias;\n";
    } else {
        die("❌ Erro: " . $e->getMessage() . "\n");
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Seed - Dados de Teste</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <h1 class="text-2xl font-bold mb-4 text-green-600">✓ Dados de Teste Criados!</h1>
        <p class="text-slate-700 mb-4">O banco foi populado com dados realistas para testes.</p>
        
        <div class="bg-slate-50 p-4 rounded-lg mb-6 text-sm space-y-2">
            <p><strong>Gerente:</strong> carlos@techcorp.com</p>
            <p><strong>Usuário:</strong> ana@techcorp.com</p>
            <p><strong>Senha:</strong> Senha@123</p>
        </div>

        <a href="test_login.php" class="block w-full bg-blue-600 text-white font-bold py-2 rounded-lg text-center hover:bg-blue-700 transition">
            Ir para Test Login
        </a>

        <a href="app/homePB.php" class="block w-full bg-slate-600 text-white font-bold py-2 rounded-lg text-center hover:bg-slate-700 transition mt-2">
            Ir para Home
        </a>
    </div>
</body>
</html>
