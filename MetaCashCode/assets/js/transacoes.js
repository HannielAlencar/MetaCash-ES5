document.addEventListener('DOMContentLoaded', () => {
    const inputBusca = document.getElementById('inputBusca');
    const filtroCategoria = document.getElementById('filtroCategoria');
    const itens = document.querySelectorAll('.item-transacao');
    const msgVazio = document.getElementById('msgVazio');

    // Função de Filtragem
    const filtrar = () => {
        const termo = inputBusca.value.toLowerCase();
        const categoria = filtroCategoria.value;
        let encontrados = 0;

        itens.forEach(item => {
            const titulo = item.getAttribute('data-titulo');
            const catItem = item.getAttribute('data-categoria');

            const bateTexto = titulo.includes(termo);
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
function toggleModal() {
    const modal = document.getElementById('modalTransacao');
    if (modal.classList.contains('hidden')) {
        modal.classList.replace('hidden', 'flex');
    } else {
        modal.classList.replace('flex', 'hidden');
    }
}