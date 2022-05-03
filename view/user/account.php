<?php

use App\Config;

?>
<section class="account">
    <div class="avatar" style="background-image: url('/assets/avatar/<?= $data['user']->getAvatar() ?>')"></div>

    <div id="change_avatar">
    <?php
        foreach (Config::AVATAR as $key => $value) {
        ?>
            <a href="/index.php?c=user&a=change-avatar&avatar=<?= $key ?>" class="avatar"
               style="background-image: url('/assets/avatar/<?= $value ?>')" title="<?= $value ?>">
            </a>
        <?php
        }
    ?>
    </div>

    <h1><?= $data['user']->getUsername() ?></h1>

    <div class="flex">

        <div class="library">
            <h2>Bibliothèque</h2>

            <?php
                foreach ($data['list'] as $key => $value) {
                ?>
                    <h3>
                        <a href="/index.php?c=card&a=card-list&name=<?= array_search($key, Config::DEFAULT_LIST) ?>&id=<?= $data['user']->getId() ?>">
                            <?= $key ?>
                        </a>
                    </h3>

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

        <?php
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getId() === $data['user']->getId()) {
            ?>
                <div class="links">
                    <div>
                        <a href="/index.php?c=connection&a=log-out">Déconnexion</a>
                    </div>

                    <div>
                        <a href="/index.php?c=connection&a=change-info">Modifier vos informations personnelles</a>
                    </div>

                    <?php
                    if ($_SESSION['user']->getRole() === 'admin') {
                        ?>
                        <div>
                            <a href="/index.php?c=card&a=update-page&id">Créer une fiche</a>
                        </div>

                        <div>
                            <a href="/index.php?c=user&a=comment-list">Commentaires des utilisateurs en attente</a>
                        </div>

                        <div>
                            <a href="/index.php?c=user&a=user-list">Liste des utilisateurs</a>
                        </div>
                        <?php
                    }
                    ?>

                    <div>
                        <a class="delete" href="/index.php?c=user&a=delete-user&id=<?= $_SESSION['user']->getId() ?>">Supprimer le compte</a>
                    </div>
                </div>
            <?php
            }
        }
        ?>
    </div>

</section>