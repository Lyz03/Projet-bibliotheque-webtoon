<section class="account">
    <h1><?= $data['user']->getUsername() ?></h1>

    <div class="flex">

        <div class="library">
            <h2>Bibliothèque</h2>

            <h3><a href="">A lire</a></h3>
            <div class="flex">
                <div class="card" style="background-image: url('/assets/images/ko.jpg')">
                    <a class="link" href="?p=card"><h3>le titre qui est treeeeeeeeeeeeeeees long</h3></a>
                </div>

                <div class="card" style="background-image: url('/assets/images/ko.jpg')">
                    <a class="link" href="?p=card"><h3>le titre qui est treeeeeeeeeeeeeeees long</h3></a>
                </div>

                <div class="card" style="background-image: url('/assets/images/ko.jpg')">
                    <a class="link" href="?p=card"><h3>le titre qui est treeeeeeeeeeeeeeees long</h3></a>
                </div>
            </div>

            <h3><a href="">En cours</a></h3>
            <div class="flex">
                <div class="card" style="background-image: url('/assets/images/lio.jpg')">
                    <a class="link" href=""><h3>le titre</h3></a>
                </div>

                <div class="card" style="background-image: url('/assets/images/lio.jpg')">
                    <a class="link" href=""><h3>le titre</h3></a>
                </div>

                <div class="card" style="background-image: url('/assets/images/lio.jpg')">
                    <a class="link" href=""><h3>le titre</h3></a>
                </div>
            </div>

            <h3><a href="">lu</a></h3>
            <div class="flex">
                <div class="card" style="background-image: url('/assets/images/image3.jpg')">
                    <a class="link" href=""><h3>le titre</h3></a>
                </div>

                <div class="card" style="background-image: url('/assets/images/image3.jpg')">
                    <a class="link" href=""><h3>le titre</h3></a>
                </div>

                <div class="card" style="background-image: url('/assets/images/image3.jpg')">
                    <a class="link" href=""><h3>le titre</h3></a>
                </div>
            </div>
        </div>

        <div class="card_com">
            <div>
                fiches 3
            </div>

            <div>
                commentaires 1
            </div>

            <div>
                <a href="">Modifier vos informations personnelles</a>
                <a href="/index.php?c=connection&a=log-out">Déconnexion</a>
            </div>
        </div>

    </div>

</section>