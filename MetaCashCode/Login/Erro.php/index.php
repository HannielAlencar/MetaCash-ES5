<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Inválido</title>
    <link rel="stylesheet" href="Erro.css/style.css">
</head>
<body>

    <div class="container">
        <div class="icon-circle">
            <span>!</span>
        </div>
        
        <h2>Link Inválido</h2>
        
        <p>
            Este link de redefinição de senha é inválido<br>
            ou expirou.
        </p>

        <?php
            // Lógica de URL para o botão
            $url_recuperacao = "solicitar_token.php"; 
        ?>

<a href="<?= '../Esquecer-senha.php/esqueceu-senha.php' ?>" class="btn-recovery">
    Solicitar Nova Recuperação
</a>
    </div>

</body>
</html>