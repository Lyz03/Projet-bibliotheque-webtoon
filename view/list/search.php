<div class="card_container">
    <?php
        foreach ($data['cards'] as $value) {
        ?>
            <div class="card" style="background-image: url('/assets/images/<?= $value->getImage() ?>')">
                <a class="link" href="/index.php?c=card&a=card-page&id=<?= $value->getId() ?>">
                    <h3><?= $value->getTitle() ?></h3>
                </a>
            </div>
        <?php
        }
    ?>
</div>
