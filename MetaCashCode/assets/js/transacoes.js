document.addEventListener('DOMContentLoaded', () => {
    const inputBusca = document.getElementById('inputBusca');
    const filtroCategoria = document.getElementById('filtroCategoria');
    const itens = document.querySelectorAll('.item-transacao');
    const msgVazio = document.getElementById('msgVazio');

    // Função de Filtragem
    const filtrar = () => {
        const termo = (inputBusca.value || '').toLowerCase();
        const categoria = (filtroCategoria.value || '').toLowerCase().trim();
        let encontrados = 0;

        itens.forEach(item => {
            const titulo = (item.getAttribute('data-titulo') || '').toLowerCase();
            const catItem = (item.getAttribute('data-categoria') || '').toLowerCase().trim();

            const bateTexto = titulo.includes(termo);
            // Comparação case-insensitive e sem espaços extras para categorias
            const bateCategoria = (categoria === 'todas' || catItem === categoria);

            if (bateTexto && bateCategoria) {
                item.style.display = 'flex';
                encontrados++;
            } else {
                item.style.display = 'none';
            }
        });

        msgVazio.classList.toggle('hidden', encontrados > 0);
    };

    // Listeners para os inputs
    inputBusca.addEventListener('keyup', filtrar);
    filtroCategoria.addEventListener('change', filtrar);
});

// Controle do Modal
function toggleModal(modalId = 'modalTransacao') {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.toggle('hidden');
    modal.classList.toggle('flex');
}
