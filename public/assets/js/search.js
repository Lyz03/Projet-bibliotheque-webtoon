const search = document.querySelector('input[type=search]');

if (search) {
    search.addEventListener('keyup', function () {

        if (search.value !== '') {
            const xhr = new XMLHttpRequest();
            xhr.responseType = 'json';
            xhr.open('POST', '/index.php?c=api');

            const json = {
                search: search.value
            }

            xhr.onload = function() {
                if (xhr.status === 404) {
                    alert('Aucun endpoint trouvé !');
                    return;
                } else if (xhr.status === 400) {
                    alert('Un paramètre est manquant');
                    return;
                }


                const response = xhr.response;
                console.log(response);

            }

            xhr.send(JSON.stringify(json))
        }
    })
}