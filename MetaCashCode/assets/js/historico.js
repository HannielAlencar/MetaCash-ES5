    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

    // Fecha modais ao clicar fora
    window.onclick = function(event) {
        const mRel = document.getElementById('modalRelatorio');
        if (event.target == mRel) toggleModal('modalRelatorio');
    }

    // Filtros dinâmicos em tempo real
    const inputBusca = document.getElementById('inputBusca');
    const filtroTipo = document.getElementById('filtroTipo');
    const filtroData = document.getElementById('filtroData');
    const items = document.querySelectorAll('.item-registro');
    const msgVazio = document.getElementById('msgVazio');
    const contadorRegistros = document.getElementById('contadorRegistros');

    function filtrarTabela() {
        const buscaQuery = inputBusca.value.toLowerCase().trim();
        const tipoQuery = filtroTipo.value.toLowerCase();
        const dataQuery = filtroData.value.trim();

        let visiveis = 0;

        items.forEach(item => {
            const desc = item.dataset.desc;
            const tipo = item.dataset.tipo;
            const data = item.dataset.data;

            const matchesBusca = buscaQuery === '' || desc.includes(buscaQuery) || tipo.includes(buscaQuery) || data.includes(buscaQuery);
            const matchesTipo = tipoQuery === 'todos' || tipo === tipoQuery;
            const matchesData = dataQuery === '' || data.includes(dataQuery);

            if (matchesBusca && matchesTipo && matchesData) {
                item.classList.remove('hidden');
                visiveis++;
            } else {
                item.classList.add('hidden');
            }
        });

        if (visiveis === 0) {
            msgVazio.classList.remove('hidden');
        } else {
            msgVazio.classList.add('hidden');
        }

        contadorRegistros.innerText = visiveis + (visiveis === 1 ? ' registro' : ' registros');
    }

    inputBusca.addEventListener('input', filtrarTabela);
    filtroTipo.addEventListener('change', filtrarTabela);
    filtroData.addEventListener('input', filtrarTabela);

    // Função de remover registro dinamicamente (Visual)
    function removerRegistro(button) {
        const row = button.closest('.item-registro');
        if (confirm('Tem certeza de que deseja remover permanentemente este registro do histórico?')) {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateY(15px)';
            setTimeout(() => {
                row.remove();
                filtrarTabela();
            }, 300);
        }
    }
    