  
const form = document.getElementById('loginForm');

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
 