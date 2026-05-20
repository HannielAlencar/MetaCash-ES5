<?php
// Configurações de conexão (exemplo)
// $pdo = new PDO("pgsql:host=localhost;dbname=metacash", "usuario", "senha");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if ($email) {
        // 1. Gerar um token único e seguro
        $token = bin2hex(random_bytes(32));
        
        // 2. Definir expiração (ex: 1 hora a partir de agora)
        $expiracao = date("Y-m-d H:i:s", strtotime('+1 hour'));

        /* 3. Lógica de Banco de Dados:
           Aqui você salvaria o $token e a $expiracao no banco para o usuário com este $email
        */

        // 4. Configuração do link de redefinição
        // Ajustado para o caminho que você solicitou
        $link = "RedefinirSenha.php/redefinir-senha.php?token=" . $token;

        // Simulação de envio de e-mail
        $assunto = "Recuperação de Senha - MetaCash";
        $mensagem = "Clique no link para redefinir sua senha: " . $link;
        $headers = "From: no-reply@metacash.com";

        // mail($email, $assunto, $mensagem, $headers); 

        // --- REDIRECIONAMENTO ---
        // Aqui redirecionamos o usuário para a página de sucesso/instruções
        header("Location: ../RedefinirSenha.php/redefinir-senha.php?status=enviado&email=" . urlencode($email));
        exit(); 

    } else {
        // Se o e-mail for inválido ou vazio
        header("Location: ../esqueceu-senha.php?erro=email_invalido");
        exit();
    }
}
?>