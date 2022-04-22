<h1>home</h1>

<section>
    <h2>Les plus populaires</h2>

    <div class="card_container">
    <?php
        foreach ($data['popular'] as $value) {
            ?>
            <div class="card" style="background-image: url('/assets/images/<?= $value->getImage() ?>')">
                <a class="link" href="/index.php?c=card&a=card-page&id=<?= $value->getId() ?>">
                    <h3><?= $value->getTitle() ?></h3>
                </a>
            </div>
            <?php
        }
    ?>

        <a class="show_more" href="?p=seeAll">Voir plus ></a>
    </div>
</section>

<section>
    <h2>Les ajouts récents</h2>

    <div class="card_container">

    <?php
        foreach ($data['recent'] as $value) {
        ?>
            <div class="card" style="background-image: url('/assets/images/<?= $value->getImage() ?>')">
                <a class="link" href="/index.php?c=card&a=card-page&id=<?= $value->getId() ?>">
                    <h3><?= $value->getTitle() ?></h3>
                </a>
            </div>
        <?php
        }
    ?>

        <a class="show_more" href=?p=seeAll">Voir plus ></a>
    </div>
</section>

<section>
    <h2>Découverte par genre</h2>

    <div class="center">
        <div class="seeAll center">
            <a href="">Fantastique</a>
            <a href="">Comédie</a>
            <a href="">Action</a>
            <a href="">Tranche de vie</a>
            <a href="">Romance</a>
            <a href="">Super Hero</a>
            <a href="">Sport</a>
            <a href="">SF</a>
            <a href="">Horreur</a>
        </div>
    </div>
</section>