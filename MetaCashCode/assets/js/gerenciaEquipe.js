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

    // Filtros em tempo real
    const inputBusca = document.getElementById('inputBusca');
    const filtroCargo = document.getElementById('filtroCargo');
    const cards = document.querySelectorAll('.card-membro');
    const msgVazio = document.getElementById('msgVazio');

    function filtrarEquipe() {
        const query = inputBusca.value.toLowerCase().trim();
        const cargo = filtroCargo.value.toLowerCase();
        let visiveis = 0;

        cards.forEach(card => {
            const nome = card.dataset.nome;
            const cargoCard = card.dataset.cargo;

            const matchesBusca = query === '' || nome.includes(query);
            const matchesCargo = cargo === 'todos' || cargoCard === cargo;

            if (matchesBusca && matchesCargo) {
                card.classList.remove('hidden');
                visiveis++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (visiveis === 0) {
            msgVazio.classList.remove('hidden');
        } else {
            msgVazio.classList.add('hidden');
        }
    }

    inputBusca.addEventListener('input', filtrarEquipe);
    filtroCargo.addEventListener('change', filtrarEquipe);
   