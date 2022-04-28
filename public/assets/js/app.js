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