document.addEventListener("DOMContentLoaded", () => {
    const navbar = document.querySelector('.header .flex .navbar');
    const loginButton = document.getElementById('login');
    const registerButton = document.getElementById('register');
    const container = document.getElementById('container');

    if (navbar) {
        document.querySelector('#menu-btn')?.addEventListener('click', () => {
            navbar.classList.toggle('active');
        });

        window.addEventListener('scroll', () => {
            navbar.classList.remove('active');
        });
    }

    document.querySelectorAll('input[type="number"]').forEach(inputNumber => {
        inputNumber.addEventListener('input', () => {
            if (inputNumber.value.length > inputNumber.maxLength) {
                inputNumber.value = inputNumber.value.slice(0, inputNumber.maxLength);
            }
        });
    });

    if (loginButton && registerButton && container) {
        loginButton.onclick = () => container.classList.remove('active');
        registerButton.onclick = () => container.classList.add('active');
    } else {
        console.error("Uno o más elementos no se encontraron en el DOM.");
    }
});
