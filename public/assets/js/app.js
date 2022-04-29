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

// Js verification for connection form
const connection = document.querySelector('#connection');

if (connection) {
    const email = document.querySelector('#connection input[type=email]');
    const password = document.querySelector('#connection input[type=password]');
    const submit = document.querySelector('#connection input[type=submit]');

    submit.addEventListener('click', e => {
        // email
        if (email.value.length < 8 || email.value.length > 100)
            email.setCustomValidity("L'email doit faire entre 8 et 100 caractères");
        else
            email.setCustomValidity('');

        // password
        if (password.value.length < 8 || password.value.length > 255)
            password.setCustomValidity('Le mot de passe doit faire au moins 8 caractère');
        else
            password.setCustomValidity('');
    })
}

// Js verification for register form
const register = document.querySelector('#register');

if (register) {
    const email = document.querySelector('#register input[type=email]');
    const password = document.querySelectorAll('#register input[type=password]');
    const username = document.querySelector('#register input[type=text]');
    const submit = document.querySelector('#register input[type=submit]');

    submit.addEventListener('click', e => {
        // email
        if (email.value.length < 8 || email.value.length > 100)
            email.setCustomValidity("L'email doit faire entre 8 et 100 caractères");
        else
            email.setCustomValidity('');

        // username
        if (username.value.length < 3 || username.value.length > 45)
            username.setCustomValidity("Le pseudo doit faire entre 3 et 45 caractères");
        else
            username.setCustomValidity('');

        // password (!!! 2 champ!!!!)
        if (password[0].value.length < 8 || password[0].value.length > 255)
            password[0].setCustomValidity('Le mot de passe doit faire au moins 8 caractère');
        else
            password[0].setCustomValidity('');

        const regExp = new RegExp(/^(?=.*[!@#$%^&*-\\])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/);
        if (regExp.test(password[0].value))
            password[0].setCustomValidity('');
        else
            password[0].setCustomValidity("Le mot de passe n'est pas assez sécurisé");

        if (password[0].value !== password[1].value)
            password[0].setCustomValidity('Les mots de passe ne corespondent pas');
        else
            password[0].setCustomValidity('');
    })
}

// JS verification for update card
const updateCard = document.querySelector('.update_card');
if (updateCard) {
    const submit = document.querySelector('.update_card');
    const text = document.querySelectorAll('.update_card input[type=text]');
    const dateStart = document.querySelector('.update_card input[type=number]');
    const synopsis = document.querySelector('.update_card textarea');

    submit.addEventListener('click', function (e) {
        // title
        if (text[0].value.length < 1 || text[0].value.length > 90)
            text[0].setCustomValidity('Le titre doit faire entre 1 et 90 caractères');
        else
            text[0].setCustomValidity('');

        // script
        if (text[1].value.length < 1 || text[1].value.length > 60)
            text[1].setCustomValidity('Le champ scénariste doit faire entre 1 et 60 caractères');
        else
            text[1].setCustomValidity('');

        // drawing
        if (text[2].value.length < 1 || text[2].value.length > 60)
            text[2].setCustomValidity('Le champ dessinateur doit faire entre 1 et 60 caractères');
        else
            text[2].setCustomValidity('');

        // date start
        if (parseInt(dateStart.value) < 1900 || parseInt(dateStart.value) > 2800)
            dateStart.setCustomValidity("L'année de début de publication doit être comprise entre 1900 et 2800");
        else
            dateStart.setCustomValidity('');

        // synopsis
        if (synopsis.value.length < 10 || synopsis.value.length > 600)
            synopsis.setCustomValidity('Le synopsis doit faire entre 10 et 600 caractères');
        else
            synopsis.setCustomValidity('');

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
const deleteLink = document.querySelectorAll('a.delete');

if (deleteLink) {
    const deleteDiv = document.querySelector('div.delete');
    const closeDelete = document.querySelector('button.closeDelete');
    const confirm = document.querySelector('button.confirm');
    let link = '';

    deleteLink.forEach(value => {
        value.addEventListener('click', function (e) {
            e.preventDefault()
            deleteDiv.style.display = 'block';
            link = value.href;
        })
    })



    closeDelete.addEventListener('click', function () {
        deleteDiv.style.display = 'none';
    })

    confirm.addEventListener('click', function () {
        deleteDiv.style.display = 'none';
        window.location.replace(link);
    })
}

// Close / open webtoon list option
const webtoonListButton = document.querySelector('button.show_list ')
let b = 0;

if (webtoonListButton) {

    const webtoonList = document.querySelector('div.webtoon_list')
    const highlight = document.querySelector('div.highlight')
    webtoonListButton.addEventListener('click', () => {

        if (b === 0) {
            webtoonList.style.display = 'block';
            highlight.style.backgroundColor = "#bd4b93";
            highlight.style.transform = "rotate(2deg)";
            webtoonListButton.style.transform = "rotate(-2deg)";
            b++
        } else if (b === 1) {
            webtoonList.style.display = 'none';
            highlight.style.backgroundColor = "var(--mainColor)"
            highlight.style.transform = "rotate(-4deg)"
            webtoonListButton.style.transform = "rotate(4deg)";
            b--
        }

    })
}

// SeeAll sort by
const sortBySelect = document.querySelector('#sortBy');

if (sortBySelect) {
    sortBySelect.addEventListener("change", function () {
        let curentLocation = window.location.search;
        let param = new URLSearchParams (curentLocation);
        let type = param.get('type');

        if (type !== null) {
            window.location.replace('/index.php?c=card&a=sort-cards&sort=' + this.value + '&type=' + type);
        } else {
            window.location.replace('/index.php?c=card&a=sort-cards&sort=' + this.value + '&type=0');
        }

    })
}

// Display change_avatar
const avatar = document.querySelector('.account .avatar');

if (avatar) {
    const changeAvatar = document.querySelector('#change_avatar');

    avatar.addEventListener('click', function () {
        changeAvatar.style.display = 'flex';
    })
}