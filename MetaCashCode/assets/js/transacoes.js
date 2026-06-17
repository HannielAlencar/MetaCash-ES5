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



function toggleModal(id) {
const modal = document.getElementById(id);
if (modal) {
    modal.classList.toggle('hidden');
    modal.classList.toggle('flex');
    }
}

document.getElementById('inputBusca')?.addEventListener('input', function() {
const query = this.value.toLowerCase();
const items = document.querySelectorAll('.item-transacao');
let found = false;
items.forEach(item => {
    const titulo = item.dataset.titulo || '';
    const categoria = item.dataset.categoria || '';
    const visible = titulo.includes(query) || categoria.includes(query);
    item.classList.toggle('hidden', !visible);
    if (visible) found = true;
});
document.getElementById('msgVazio')?.classList.toggle('hidden', found || query === '');
});

window.onclick = function(event) {
    const mRel = document.getElementById('modalRelatorio');
    const mTrans = document.getElementById('modalTransacao');
    const mExcluir = document.getElementById('modalExcluir');
    if (event.target == mRel) toggleModal('modalRelatorio');
    if (event.target == mTrans) toggleModal('modalTransacao');
    if (event.target == mExcluir) toggleModal('modalExcluir');
}

function toggleRelatorioModal() {
    const modal = document.getElementById('modalRelatorio');
    if (modal) {
        modal.classList.toggle('hidden');
        modal.classList.toggle('flex');
    }
}

document.querySelectorAll('.btnExcluirTransacao').forEach(button => {
    button.addEventListener('click', function() {
        const url = this.dataset.deleteUrl;
        const confirmLink = document.getElementById('confirmDeleteLink');
        if (confirmLink) {
            confirmLink.setAttribute('href', url);
            toggleModal('modalExcluir');
        }
    });
});
    

(function() {
    try {
        const theme = JSON.parse(localStorage.getItem('theme'));
        if (theme) {
            const root = document.documentElement;
            // Mapeamento das chaves do seu LocalStorage para as variáveis CSS
            if (theme.primary) root.style.setProperty('--color-primary', theme.primary);
            if (theme.sidebar) root.style.setProperty('--color-sidebar', theme.sidebar);
            if (theme.background) root.style.setProperty('--color-background', theme.background);
            if (theme.card) root.style.setProperty('--color-card', theme.card);
            if (theme.text) root.style.setProperty('--color-text', theme.text);
        }
    } catch (e) {
        console.warn('Tema não definido ou corrompido, usando padrão.');
    }
})();
   

   
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: 'var(--color-primary)',
                'primary-hover': 'var(--color-primary-hover)',
                sidebar: 'var(--color-sidebar)',
                background: 'var(--color-background)',
                card: 'var(--color-card)',
                text: 'var(--color-text)',
                success: 'var(--color-success)',
                danger: 'var(--color-danger)'
            }
        }
    }
}
    