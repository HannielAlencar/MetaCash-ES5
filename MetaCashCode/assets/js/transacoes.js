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
            const bateCategoria = (categoria === 'todas' || catItem === categoria);

            if (bateTexto && bateCategoria) {
                item.style.display = 'flex';
                encontrados++;
            } else {
                item.style.display = 'none';
            }
        });

        if (msgVazio) {
            msgVazio.classList.toggle('hidden', encontrados > 0);
        }
    };

    // Listeners para os inputs
    if (inputBusca) inputBusca.addEventListener('keyup', filtrar);
    if (filtroCategoria) filtroCategoria.addEventListener('change', filtrar);

    // Tratamento de sucesso via URL
    const params = new URLSearchParams(window.location.search);
    const sucesso = params.get('success');
    const popup = document.getElementById('popupSucesso');
    
    if (sucesso === '1' && popup) {
        popup.classList.remove('hidden');
        popup.style.display = 'block';

        setTimeout(() => {
            popup.classList.add('hidden');
            popup.style.display = 'none';
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

// Função de Salvar e Recarregar
async function adicionarEConfirmar(event) {
    if (event) event.preventDefault();
    
    const form = document.getElementById('formTransacao');
    const formData = new FormData(form);
    const popup = document.getElementById('popupSucesso');
    
    // Pega a URL diretamente do atributo do formulário
    const actionUrl = form.getAttribute('data-url');

    try {
        const response = await fetch(actionUrl, {
            method: 'POST',
            body: formData
        });

        const text = await response.text();
        
        // Se retornar qualquer coisa que não comece com '{', é um erro de servidor/HTML
        if (!text.trim().startsWith('{')) {
            console.error('ERRO DETECTADO:', text);
            alert('Erro: O servidor retornou uma página em vez de dados. Verifique o console (F12) para ver o erro.');
            return false;
        }

        const result = JSON.parse(text);

        if (result.status === 'sucesso') {
            toggleModal('modalTransacao');
            if (popup) {
                popup.style.display = 'block';
                popup.classList.remove('hidden');
            }
            form.reset();
            setTimeout(() => { window.location.reload(); }, 1000);
        } else {
            alert('Erro: ' + (result.mensagem || 'Erro desconhecido.'));
        }
    } catch (error) {
        console.error('Erro crítico:', error);
        alert('Erro ao conectar com o servidor.');
    }
    
    return false;
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