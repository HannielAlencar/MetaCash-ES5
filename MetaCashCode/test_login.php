<?php
/**
 * ARQUIVO DE TESTE - SIMULA SESSÕES DE USUÁRIO E GERENTE
 * Use para testar páginas sem passar pelo fluxo de login
 * 
 * Exemplos:
 * - http://localhost/test_login.php?role=gerente&redirect=/app/dashboardGerente.php
 * - http://localhost/test_login.php?role=usuario&redirect=/app/dashboardUsuario.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

$role = $_GET['role'] ?? 'gerente'; // gerente ou usuario
$redirect = $_GET['redirect'] ?? '/app/dashboardGerente.php';

// Limpa sessão anterior
session_destroy();
session_start();

try {
    // Busca primeiro gerente (ou cria dados simulados)
    if ($role === 'gerente') {
        $stmt = $pdo->prepare("SELECT u.id_usuario, u.id_empresa, u.nome_completo, u.email, u.matricula, u.cpf, u.nivel_permissao 
                             FROM usuarios u 
                             WHERE u.nivel_permissao = 'Gerente' 
                             LIMIT 1");
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['id_empresa'] = $user['id_empresa'];
            $_SESSION['nome_usuario'] = $user['nome_completo'];
            $_SESSION['email_usuario'] = $user['email'];
            $_SESSION['matricula'] = $user['matricula'];
            $_SESSION['cpf_usuario'] = $user['cpf'];
            $_SESSION['nivel_permissao'] = $user['nivel_permissao'];
            header("Location: " . $redirect);
            exit();
        } else {
            // Se não encontra gerente, cria dados fictícios
            $_SESSION['id_usuario'] = 999;
            $_SESSION['id_empresa'] = 1;
            $_SESSION['nome_usuario'] = 'Gerente Teste';
            $_SESSION['email_usuario'] = 'gerente@teste.com';
            $_SESSION['matricula'] = 'G001';
            $_SESSION['cpf_usuario'] = '000.000.000-00';
            $_SESSION['nivel_permissao'] = 'Gerente';
            header("Location: " . $redirect);
            exit();
        }

    } elseif ($role === 'usuario') {
        $stmt = $pdo->prepare("SELECT u.id_usuario, u.id_empresa, u.nome_completo, u.email, u.matricula, u.cpf, u.nivel_permissao 
                             FROM usuarios u 
                             WHERE u.nivel_permissao != 'Gerente' 
                             LIMIT 1");
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['id_empresa'] = $user['id_empresa'];
            $_SESSION['nome_usuario'] = $user['nome_completo'];
            $_SESSION['email_usuario'] = $user['email'];
            $_SESSION['matricula'] = $user['matricula'];
            $_SESSION['cpf_usuario'] = $user['cpf'];
            $_SESSION['nivel_permissao'] = $user['nivel_permissao'];
            header("Location: " . $redirect);
            exit();
        } else {
            // Se não encontra usuário, cria dados fictícios
            $_SESSION['id_usuario'] = 998;
            $_SESSION['id_empresa'] = 1;
            $_SESSION['nome_usuario'] = 'Usuário Teste';
            $_SESSION['email_usuario'] = 'usuario@teste.com';
            $_SESSION['matricula'] = 'U001';
            $_SESSION['cpf_usuario'] = '111.111.111-11';
            $_SESSION['nivel_permissao'] = 'Membro';
            header("Location: " . $redirect);
            exit();
        }
    }
} catch (PDOException $e) {
    error_log('Erro em test_login.php: ' . $e->getMessage());
    // Fallback: cria sessão fictícia
    $_SESSION['id_usuario'] = 999;
    $_SESSION['id_empresa'] = 1;
    $_SESSION['nome_usuario'] = 'Teste';
    $_SESSION['email_usuario'] = 'teste@teste.com';
    $_SESSION['matricula'] = 'TST001';
    $_SESSION['cpf_usuario'] = '000.000.000-00';
    $_SESSION['nivel_permissao'] = ($role === 'gerente' ? 'Gerente' : 'Membro');
    header("Location: " . $redirect);
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Teste - Simular Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <h1 class="text-2xl font-bold mb-6 text-slate-800">Simular Login (Teste)</h1>
        <p class="text-sm text-slate-500 mb-6">Escolha um perfil e a página para testar:</p>

        <form method="GET" class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Perfil</label>
                <select name="role" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="gerente">Gerente</option>
                    <option value="usuario">Usuário</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Página para Testar</label>
                <select name="redirect" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="/app/dashboardGerente.php">Dashboard Gerente</option>
                    <option value="/app/dashboardUsuario.php">Dashboard Usuário</option>
                    <option value="/app/homeGerente.php">Home Gerente</option>
                    <option value="/app/homePB.php">Home PB</option>
                    <option value="/app/gerenciaEquipe.php">Gerência de Equipe</option>
                    <option value="/app/transacoes.php">Transações</option>
                    <option value="/app/transacoesGerente.php">Transações Gerente</option>
                    <option value="/app/historico.php">Histórico</option>
                    <option value="/app/perfil.php">Perfil</option>
                    <option value="/app/relatorio.php">Relatório</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition">
                Simular Login e Ir
            </button>
        </form>

        <p class="text-xs text-slate-500 mt-6 border-t pt-4">
            <strong>Nota:</strong> Este arquivo é apenas para desenvolvimento e testes. Não use em produção.
        </p>
    </div>
</body>
</html>
