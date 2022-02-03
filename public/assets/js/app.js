// connection page : register display block
const connectionForm = document.querySelector('#connection');
const registerForm = document.querySelector('#register');
const createAccountSpan = document.querySelector('span.createAccount');
const createAccountP = document.querySelector('p.createAccount');


if (createAccountSpan) {
    createAccountSpan.addEventListener('click', () => {

        registerForm.style.display = 'block';
        connectionForm.style.display = 'none';
        createAccountP.style.display = 'none';

    })
}