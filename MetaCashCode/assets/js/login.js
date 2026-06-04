  
const form = document.getElementById('loginForm');

function updatePasswordToggleState(target) {
    const icon = document.querySelector(`.toggle-password[data-target="${target.id}"]`);
    if (!icon) return;
    if (target.value.trim() === '') {
        icon.classList.add('disabled');
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        target.type = 'password';
    } else {
        icon.classList.remove('disabled');
    }
}

function setupPasswordToggle() {
    document.querySelectorAll('.toggle-password').forEach(icon => {
        const target = document.getElementById(icon.dataset.target);
        if (!target) return;
        updatePasswordToggleState(target);
        target.addEventListener('input', () => updatePasswordToggleState(target));
        icon.addEventListener('click', () => {
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
}

setupPasswordToggle();

if (form) {
    form.addEventListener('submit', (e) => {
        const email = document.getElementById('email')?.value || '';
        const password = document.getElementById('password')?.value || '';
        const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;

        if (email.trim() === '' || password.trim() === '') {
            e.preventDefault();
            alert('Por favor, preencha todos os campos.');
            return;
        }
        if (!emailPattern.test(email)) {
            e.preventDefault();
            alert('Por favor, insira um formato de e-mail valido.');
        }
    });
}
 