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
    const params = new URLSearchParams(window.location.search);
    const sucesso = params.get('success');
    const popup = document.getElementById('popupSucesso');
    if (sucesso === '1' && popup) {
        popup.classList.remove('hidden');
        popup.classList.add('fade-in-out');

        setTimeout(() => {
            popup.classList.add('hidden');
            popup.classList.remove('fade-in-out');
            params.delete('success');
            const newUrl = `${window.location.pathname}${params.toString() ? `?${params}` : ''}`;
            window.history.replaceState({}, '', newUrl);
        }, 3000);
    }
});

// Controle do Modal
function toggleModal(modalId = 'modalTransacao') {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.toggle('hidden');
    modal.classList.toggle('flex');
}

function adicionarEConfirmar() {
    document.getElementById('formTransacao').submit();
}

function toggleRelatorioModal() {
    toggleModal('modalRelatorio');
}

// Fecha modais ao clicar fora
window.onclick = function(event) {
    const mRel = document.getElementById('modalRelatorio');
    const mTra = document.getElementById('modalTransacao');
    if (event.target == mRel) toggleModal('modalRelatorio');
    if (event.target == mTra) toggleModal('modalTransacao');
}

