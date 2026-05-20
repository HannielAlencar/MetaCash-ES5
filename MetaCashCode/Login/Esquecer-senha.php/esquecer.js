document.getElementById('form-validacao').addEventListener('submit', function(e) {
    e.preventDefault(); // Impede o recarregamento imediato da página

    // Aqui você faria a chamada para o seu PHP (via Fetch ou AJAX)
    // Abaixo, um exemplo de lógica baseada na expiração:
    
    const codigoExpirado = false; // Esta variável viria da resposta do seu banco de dados/servidor

    if (!codigoExpirado) {
        // Se o código for válido e NÃO expirou
        window.location.href = "../EmailCheck.php/index.php"; 
    } else {
        // Se o código expirou ou é inválido
        window.location.href = "../Falha.php/falha.php";
    }
});