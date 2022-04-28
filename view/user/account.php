<?php

use App\Config;

?>
<section class="account">
    <h1><?= $data['user']->getUsername() ?></h1>

    <div class="flex">

        <div class="library">
            <h2>Bibliothèque</h2>

            <?php
                foreach ($data['list'] as $key => $value) {
                ?>
                    <h3><a href="/index.php?c=card&a=card-list&name=<?= array_search($key, Config::DEFAULT_LIST) ?>"><?= $key ?></a></h3>

                    <div class="flex">
                <?php
                    foreach ($value as $item) {
                    ?>
                        <div class="card" style="background-image: url('/assets/images/<?= $item->getCard()->getImage() ?>')">
                            <a class="link" href="?c=card&a=card-page&id=<?= $item->getCard()->getId() ?>">
                                <h3><?= $item->getCard()->getTitle() ?></h3>
                            </a>
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
                ?>
                    <div>
                        <a href="/index.php?c=card&a=update-page&id">Créer une fiche</a>
                    </div>

                    <div>
                        <a href="/index.php?c=user&a=comment-list">Voir les commentaires des utilisateurs</a>
                    </div>
                <?php
                }
            ?>
        </div>
    </div>

</section>