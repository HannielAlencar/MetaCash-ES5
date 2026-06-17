// Função para destacar o botão ativo
document.addEventListener('DOMContentLoaded', function() {
    const currentPage = '<?php echo $current_page; ?>';
    const navButtons = document.querySelectorAll('.nav-btn');
    
navButtons.forEach(btn => {
    const btnPage = btn.getAttribute('data-page');
    if (btnPage === currentPage) {
            // Remove classes de outros botões
        navButtons.forEach(b => {
            b.classList.remove('bg-[#2dd4bf]', 'text-white', 'shadow-lg');
            b.classList.add('text-gray-400', 'hover:bg-slate-800');
        });
            // Adiciona classes ao botão ativo
            btn.classList.remove('text-gray-400', 'hover:bg-slate-800');
            btn.classList.add('bg-[#2dd4bf]', 'text-white', 'shadow-lg');
        }
    });
});

function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

    // Fecha o modal ao clicar fora dele
    window.addEventListener('click', function(event) {
        const modalRelatorio = document.getElementById('modalRelatorio');
        if (event.target === modalRelatorio) {
            toggleModal('modalRelatorio');
        }
    });
