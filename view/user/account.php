<section class="account">
    <h1><?= $data['user']->getUsername() ?></h1>

    <div class="flex">

        <div class="library">
            <h2>Bibliothèque</h2>

            <?php
                foreach ($data['list'] as $key => $value) {
                ?>
                    <h3><a href=""><?= $key ?></a></h3>

                    <div class="flex">
                <?php
                    foreach ($value as $item) {
                    ?>
                        <div class="card" style="background-image: url('/assets/images/<?= $item->getCard()->getImage() ?>')">
                            <a class="link" href="?p=card"><h3><?= $item->getCard()->getTitle() ?></h3></a>
                        </div>
                    <?php
                    }
                ?>
                    </div>
                <?php
                }
            ?>

        </div>

        <div class="card_com">

            <div>
                commentaires 1
            </div>

            <div>
                <a class="delete" href="/index.php?c=user&a=delete-user&id=<?= $_SESSION['user']->getId() ?>">Supprimer le compte</a>
            </div>

            <div>
                <a href="">Modifier vos informations personnelles</a>
            </div>

            <div>
                <a href="/index.php?c=connection&a=log-out">Déconnexion</a>
            </div>

            <?php
            if ($_SESSION['user']->getRole() === 'admin') {
              echo '<a href="/index.php?c=card&a=update-page&id">Créer une fiche</a>';
            }
            ?>
            <div>

            </div>
        </div>

    </div>

</section>