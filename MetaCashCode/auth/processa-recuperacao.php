<?php

require_once __DIR__ . '/../config.php';

// Carrega o PHPMailer manualmente da pasta que criamos
require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: esqueceuSenha.php');
    exit();
}

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

if (!$email) {
    header('Location: esqueceuSenha.php?erro=email_invalido');
    exit();
}

$token = bin2hex(random_bytes(32));
$link = 'http://localhost/auth/redefinirSenha.php?token=' . $token;

try {
    // 1. Primeiro, precisamos descobrir o id_usuario com base no e-mail digitado
    $stmt_user = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :email LIMIT 1");
    $stmt_user->execute([':email' => $email]);
    $usuario_com_email = $stmt_user->fetch();

    if ($usuario_com_email) {
        $id_usuario = $usuario_com_email['id_usuario'];
        // Define que o token expira em 1 hora
        $data_expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // 2. Insere o registro na sua tabela correta: recuperacao_senha
        $sql = "INSERT INTO recuperacao_senha (id_usuario, token_seguro, data_expiracao, utilizado) 
                VALUES (:id_usuario, :token_seguro, :data_expiracao, false)";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_usuario'     => $id_usuario,
            ':token_seguro'   => $token, 
            ':data_expiracao' => $data_expiracao
        ]);
    } else {
        header('Location: esqueceuSenha.php?erro=email_nao_encontrado');
        exit();
    }
} catch (Exception $e) {
    header('Location: esqueceuSenha.php?erro=erro_banco');
    exit();
}

$mail = new PHPMailer(true);

try {
    // Configurações do Servidor SMTP do Gmail
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';                     // Servidor do Gmail
    $mail->SMTPAuth   = true;                                 // Ativa autenticação SMTP
    $mail->Username   = 'metacashprojeto@gmail.com';          // Seu e-mail
    $mail->Password   = 'eqkvlxqmeuettorn';                // ABAIXO: Cole aqui a Senha de App de 16 dígitos (sem espaços)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Ativa criptografia TLS
    $mail->Port       = 587;                                  // Porta de conexão TLS
    $mail->CharSet    = 'UTF-8';

    // Remetente e Destinatário
    $mail->setFrom('metacashprojeto@gmail.com', 'MetaCash Suporte');
    $mail->addAddress($email); 

    // Conteúdo do E-mail
    $mail->isHTML(true);
    $mail->Subject = 'Recuperação de Senha - MetaCash';
    
    $mail->Body    = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd;'>
            <h2 style='color: #333;'>Recuperação de Senha - MetaCash</h2>
            <p>Você solicitou a redefinição de senha para sua conta.</p>
            <p>Clique no botão abaixo para criar uma nova senha. Este link é válido por 1 hora.</p>
            <p style='text-align: center; margin: 30px 0;'>
                <a href='{$link}' style='background-color: #000000; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: bold;'>Redefinir Minha Senha</a>
            </p>
            <p style='font-size: 12px; color: #777;'>Se você não solicitou essa alteração, ignore este e-mail.</p>
        </div>
    ";
    
    $mail->AltBody = "Copie e cole o link no navegador para redefinir sua senha: " . $link;

    $mail->send();
    
    header('Location: esqueceuSenha.php?status=enviado');
    exit();

} catch (Exception $e) {
    // Para te ajudar a debugar se algo der errado, descomente a linha abaixo temporariamente:
    // echo "Erro no envio: {$mail->ErrorInfo}"; exit;
    
    header('Location: esqueceuSenha.php?erro=erro_envio');
    exit();
}