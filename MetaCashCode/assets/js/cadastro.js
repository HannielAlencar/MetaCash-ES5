const form = document.getElementById('signupForm');
const senhaInput = document.getElementById('senha');
const confirmaSenhaInput = document.getElementById('confirmaSenha');
const cpfInput = document.getElementById('cpf');
const cnpjInput = document.getElementById('cnpj');
const successPopup = document.getElementById('successPopup');

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

cpfInput.addEventListener('input', (e) => {
    let v = e.target.value.replace(/\D/g, '');
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = v;
});

cnpjInput.addEventListener('input', (e) => {
    let v = e.target.value.replace(/\D/g, '');
        v = v.replace(/^(\d{2})(\d)/, '$1.$2');
        v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
        v = v.replace(/(\d{4})(\d)/, '$1-$2');
        e.target.value = v;
});

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const cpfRegex = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
const cnpjRegex = /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/;

senhaInput.addEventListener('input', () => {
const val = senhaInput.value;
    updateReq('req-length', val.length >= 8);
    updateReq('req-upper', /[A-Z]/.test(val));
    updateReq('req-lower', /[a-z]/.test(val));
    updateReq('req-num', /\d/.test(val));
    updateReq('req-special', /[!@#$%^&*(),.?":{}|<>]/.test(val));
});

function updateReq(id, isValid) {
    const el = document.getElementById(id);
    const icon = el.querySelector('i');
    if (isValid) {
        el.classList.replace('text-slate-500', 'text-green-600');
        icon.classList.replace('fa-times', 'fa-check');
        icon.classList.replace('text-red-400', 'text-green-600');
}   else {
        el.classList.replace('text-green-600', 'text-slate-500');
        icon.classList.replace('fa-check', 'fa-times');
        icon.classList.replace('text-green-600', 'text-red-400');
}
}
form.addEventListener('submit', (e) => {
    e.preventDefault();

    // create/find client error container
    let clientErr = document.getElementById('signup-client-error');
    if (!clientErr) {
        clientErr = document.createElement('div');
        clientErr.id = 'signup-client-error';
        clientErr.style.marginBottom = '1rem';
        clientErr.style.padding = '0.75rem 1rem';
        clientErr.style.border = '1px solid #e53e3e';
        clientErr.style.background = '#fff5f5';
        clientErr.style.color = '#c53030';
        clientErr.style.borderRadius = '.75rem';
        form.parentNode.insertBefore(clientErr, form);
    }

    clientErr.textContent = '';

    if (!emailRegex.test(document.getElementById('email').value)) {
        clientErr.textContent = 'Por favor, insira um e-mail válido.';
        return clientErr.scrollIntoView({behavior: 'smooth'});
    }
    if (!cpfRegex.test(document.getElementById('cpf').value)) {
        clientErr.textContent = 'CPF inválido! Use o formato 000.000.000-00';
        return clientErr.scrollIntoView({behavior: 'smooth'});
    }
    if (!cnpjRegex.test(document.getElementById('cnpj').value)) {
        clientErr.textContent = 'CNPJ inválido! Use o formato 00.000.000/0000-00';
        return clientErr.scrollIntoView({behavior: 'smooth'});
    }

    const s = senhaInput.value;
    if (s.length < 8 || !/[A-Z]/.test(s) || !/[a-z]/.test(s) || !/\d/.test(s) || !/[!@#$%^&*(),.?":{}|<>]/.test(s)) {
        clientErr.textContent = 'A senha não atende aos requisitos mínimos.';
        return clientErr.scrollIntoView({behavior: 'smooth'});
    }
    if (s !== confirmaSenhaInput.value) {
        clientErr.textContent = 'As senhas não coincidem!';
        return clientErr.scrollIntoView({behavior: 'smooth'});
    }

    // Sistema de armazenamento local para login
    localStorage.setItem('userEmail', document.getElementById('email').value);
    localStorage.setItem('userPassword', s);

    // Exibir Popup e submeter o formulário ao servidor (em vez de redirecionar para caminho estático)
    successPopup.classList.remove('hidden');
    setTimeout(() => successPopup.classList.remove('opacity-0'), 10);
    // Mostra o popup brevemente e então submete o formulário para que o servidor
    // execute a criação da conta e faça o redirecionamento correto.
    setTimeout(() => {
        successPopup.classList.add('opacity-0');
        setTimeout(() => {
            form.submit();
        }, 200);
    }, 800);
});
