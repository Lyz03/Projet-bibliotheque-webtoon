// menu : responsive
const menuSpan = document.querySelector('span.menu');
const menuDiv = document.querySelector('div.menu');
let a = 0;

if (menuSpan) {

    menuSpan.addEventListener('click', () => {

        if (a === 0) {
            menuDiv.style.display = 'block';
            a++
        } else if (a === 1) {
            menuDiv.style.display = 'none';
            a--
        }

    })
}

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

// close php error message
const close = document.querySelector('#close');
const errorDiv = document.querySelector('div.error');

if (close) {
    close.addEventListener('click', () => {
        errorDiv.remove();
        close.remove();
    })
}

// confirmation before delete
const deleteLink = document.querySelector('a.delete');

if (deleteLink) {
    const deleteDiv = document.querySelector('div.delete');
    const closeDelete = document.querySelector('button.closeDelete');
    const confirm = document.querySelector('button.confirm');

    deleteLink.addEventListener('click', function (e) {
        e.preventDefault()
        deleteDiv.style.display = 'block';
    })

    closeDelete.addEventListener('click', function () {
        deleteDiv.style.display = 'none';
    })

    confirm.addEventListener('click', function () {
        deleteDiv.style.display = 'none';
        window.location.replace(deleteLink.href);
    })
}