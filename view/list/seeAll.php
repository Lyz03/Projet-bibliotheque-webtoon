<section class="filter">
    <div>
        <select name="sortBy" id="sortBy">
            <option value="">Trier par :</option>
            <option value="popular">Les plus populaires</option>
            <option value="recent">Les plus récents</option>
            <option value="old">Les plus anciens</option>
        </select>
    </div>
    <div class="seeAll center">

        <a href="/index.php?c=card">Tout</a>
        <?php

        use App\Config;

        foreach (Config::CARD_TYPE as $key => $value) {
            ?>
            <a href="/index.php?c=card&a=kind&type=<?= $key ?>"><?= $value ?></a>
            <?php
        }
        ?>
    </div>
</section>

<section>
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
</section>