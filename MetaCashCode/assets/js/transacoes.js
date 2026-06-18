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

        if (msgVazio) {
            msgVazio.classList.toggle('hidden', encontrados > 0);
        }
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

async function adicionarEConfirmar(event) {
    console.log('adicionarEConfirmar INICIADO');
    if (event) {
        event.preventDefault();
    }
    
    const form = document.getElementById('formTransacao');
    const formData = new FormData(form);
    const popup = document.getElementById('popupSucesso');

    try {
        const actionUrl = form.getAttribute('action') || (window.location.pathname.toLowerCase().includes('transacoesgerente') ? '../app/salvarTransacaoGerente.php' : '../app/salvarTransacao.php');
        console.log('Enviando para:', actionUrl);
        
        const response = await fetch(actionUrl, {
            method: 'POST',
            body: formData
        });

        const text = await response.text();
        console.log('Resposta do servidor:', text);

        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }

        let result;
        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error('Erro ao fazer parse JSON:', text);
            throw new Error('Resposta não é um JSON válido');
        }

        if (result.status === 'sucesso') {
            console.log('Sucesso, mostrando popup');
            // Mostra o popup de sucesso
            if (popup) {
                popup.classList.remove('hidden');
                popup.style.display = 'block';
            }
            
            // Limpa o formulário
            form.reset();
            
            // Fecha o modal após 1 segundo
            setTimeout(() => {
                toggleModal('modalTransacao');
            }, 1000);
            
            // Recarrega a página para mostrar os novos dados ignorando cache
            setTimeout(() => {
                window.location.href = window.location.pathname + '?t=' + new Date().getTime();
            }, 1000);
        } else {
            console.error('Servidor retornou erro:', result);
            alert('Erro: ' + (result.mensagem || 'Erro desconhecido'));
        }
    } catch (error) {
        console.error('Erro completo:', error);
        alert('Ocorreu um erro ao conectar com o servidor: ' + error.message);
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

