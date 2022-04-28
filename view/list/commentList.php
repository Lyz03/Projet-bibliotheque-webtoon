<section class="list">
    <?php
    // le nom d'utilisateur, le titre de la carte avec son lien ?, le contenu du commentaire
    foreach ($data['comments'] as $value) {
    ?>
        <div class="center">
            <p><?= $value->getCard()->getTitle() ?></p>
            <span><?= $value->getUser()->getUsername() ?></span>
            <p><?= $value->getContent() ?></p>
            <a href="/index.php?c=user&a=validate-comment&id=<?= $value->getId() ?>">valider</a>
            <a href="/index.php?c=user&a=delete-comment&id=<?= $value->getId() ?>">supprimer</a>
        </div>
    <?php
    }
    ?>
</section>
