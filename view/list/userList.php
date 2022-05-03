<div class="list">
    <?php
    foreach ($data['users'] as $value) {
    ?>
    <div>
        <div class="avatar" style="background-image: url('assets/avatar/<?= $value->getAvatar() ?>')"></div>
        <a class="username" href="/index.php?c=user&a=user-profile&id=<?= $value->getId() ?>">
            <?= $value->getUsername() ?>
        </a>
        <p><?= $value->getEmail() ?></p>
        <p><?= $value->getRole() ?></p>

        <?php
            if ($value->getRole() === 'user') {
            ?>
                <a href="/index.php?c=user&a=change-role&id=<?= $value->getId() ?>&role=admin">
                    Changer le role pour Administrateur
                </a>
            <?php
            } elseif ($value->getRole() === 'admin') {
            ?>
                <a href="/index.php?c=user&a=change-role&id=<?= $value->getId() ?>&role=user">
                    Changer le role pour Utilisateur
                </a>
            <?php
            }
        ?>

        <div>
            <a class="delete" href="/index.php?c=user&a=delete-user&id=<?= $value->getId() ?>">supprimer</a>
        </div>
    </div>
    <?php
    }
    ?>
</div>
