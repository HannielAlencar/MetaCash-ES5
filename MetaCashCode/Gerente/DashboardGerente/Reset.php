<?php
$arquivo_db = 'banco.json';

// Se o arquivo existir, ele é deletado
if (file_exists($arquivo_db)) {
    unlink($arquivo_db);
}

// Redireciona de volta para o dashboard limpo
header("Location: index.php");
exit();