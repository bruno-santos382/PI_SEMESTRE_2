document.getElementById('registerForm').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value;

    if (password.length < 6) {
        alert('A senha deve ter pelo menos 6 caracteres.');
        event.preventDefault(); 
    }
});
