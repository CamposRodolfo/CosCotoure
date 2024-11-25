document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');
    const menuBtn = document.querySelector('#menu-btn');
    const navbar = document.querySelector('.header .flex .navbar');

    // Validar y asignar eventos
    if (menuBtn && navbar) {
        menuBtn.addEventListener('click', () => {
            navbar.classList.toggle('active');
        });

        window.addEventListener('scroll', () => {
            navbar.classList.remove('active');
        });
    }

    // Validar los botones de registro e inicio de sesión
    if (registerBtn && container) {
        registerBtn.addEventListener('click', () => {
            container.classList.add('active');
        });
    } else {
        console.error("Elemento 'register' o 'container' no encontrado.");
    }

    if (loginBtn && container) {
        loginBtn.addEventListener('click', () => {
            container.classList.remove('active');
        });
    } else {
        console.error("Elemento 'login' o 'container' no encontrado.");
    }

    // Validar inputs de tipo número
    document.querySelectorAll('input[type="number"]').forEach(inputNumber => {
        inputNumber.addEventListener('input', () => {
            if (inputNumber.value.length > inputNumber.maxLength) {
                inputNumber.value = inputNumber.value.slice(0, inputNumber.maxLength);
            }
        });
    });
});
