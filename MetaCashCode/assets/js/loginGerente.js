// Toggle password visibility on the gerente login form.
function toggleVisibility() {
    const senhaInput = document.getElementById('senha');
    if (!senhaInput) return;
    senhaInput.type = senhaInput.type === 'password' ? 'text' : 'password';
}
