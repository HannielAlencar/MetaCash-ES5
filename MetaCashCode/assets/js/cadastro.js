const form = document.getElementById('signupForm');
const senhaInput = document.getElementById('senha');
const confirmaSenhaInput = document.getElementById('confirmaSenha');
const cpfInput = document.getElementById('cpf');
const cnpjInput = document.getElementById('cnpj');
const successPopup = document.getElementById('successPopup');

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
            
    if (!emailRegex.test(document.getElementById('email').value)) {
        return alert("Por favor, insira um e-mail válido.");
}
    if (!cpfRegex.test(document.getElementById('cpf').value)) {
        return alert("CPF inválido! Use o formato 000.000.000-00");
}
    if (!cnpjRegex.test(document.getElementById('cnpj').value)) {
        return alert("CNPJ inválido! Use o formato 00.000.000/0000-00");
}

const s = senhaInput.value;
    if (s.length < 8 || !/[A-Z]/.test(s) || !/[a-z]/.test(s) || !/\d/.test(s) || !/[!@#$%^&*(),.?":{}|<>]/.test(s)) {
        return alert("A senha não atende aos requisitos mínimos.");
}
    if (s !== confirmaSenhaInput.value) {
        return alert("As senhas não coincidem!");
}

// Sistema de armazenamento local para login
localStorage.setItem('userEmail', document.getElementById('email').value);
    localStorage.setItem('userPassword', s);

// Exibir Popup
successPopup.classList.remove('hidden');
setTimeout(() => successPopup.classList.remove('opacity-0'), 10);
setTimeout(() => {
    successPopup.classList.add('opacity-0');
    setTimeout(() => {
        window.location.href = "/MetaCashCode/Usuario/Dashboard/index.php";
    }, 500);
}, 3000);
});
