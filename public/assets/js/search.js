const search = document.querySelector('input[type=search]');

if (search) {
    const div = document.querySelector('#suggestions');

    search.addEventListener('keyup', function () {

        if (search.value.trim() !== '') {
            const xhr = new XMLHttpRequest();
            xhr.responseType = 'json';
            xhr.open('POST', '/index.php?c=api');

            const json = {
                search: search.value.trim()
            }

            xhr.onload = function() {
                if (xhr.status === 404) {
                    alert('Aucun endpoint trouvé !');
                    return;
                } else if (xhr.status === 400) {
                    alert('Un paramètre est manquant');
                    return;
                }

                div.innerHTML = '';

                xhr.response.forEach(value => {
                    const a = document.createElement("a");
                    a.href = "/index.php?c=card&a=card-page&id=" + value.id;
                    a.innerText = value.title;
                    div.appendChild(a);
                })
            }

            xhr.send(JSON.stringify(json))
        } else {
            div.innerHTML = '';
        }
    })
}