<h1>home</h1>

<section>
    <h2>Les plus populaires</h2>

    <div class="card_container">
    <?php

    use App\Config;

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
        <?php
            foreach (Config::CARD_TYPE as $key => $value) {
            ?>
                <a href="/index.php?c=card&a=kind&type=<?= $key ?>"><?= $value ?></a>
            <?php
            }
        ?>
        </div>
    </div>
</section>