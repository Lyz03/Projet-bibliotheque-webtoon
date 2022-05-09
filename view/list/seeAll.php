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
            <a href="/index.php?c=card&a=kind&type=<?= $key ?>&page=1"><?= $value ?></a>
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

    <div class="pages">
    <?php
        if (isset($data['type']) && isset($data['sortCard'])) {
            for ($i = 0; $i < $data['page']; $i++) {
                ?>
                <a id="page<?= $i + 1 ?>"
                   href="/index.php?c=card&a=sort-cards&sort=<?= $data['sortCard'] ?>&page=<?= $i + 1 ?>&type=<?= $data['type'] ?>">
                    <?= $i + 1 ?></a>
                <?php
            }
        } elseif (isset($data['type'])) {
            for ($i = 0; $i < $data['page']; $i++) {
                ?>
                <a id="page<?= $i + 1 ?>"
                   href="/index.php?c=card&a=kind&type=<?= $data['type'] ?>&page=<?= $i + 1 ?>"><?= $i + 1 ?></a>
                <?php
            }
        } elseif (isset($data['sortCard'])) {
            for ($i = 0; $i < $data['page']; $i++) {
                ?>
                <a id="page<?= $i + 1 ?>"
                   href="/index.php?c=card&a=sort-cards&sort=<?= $data['sortCard'] ?>&page=<?= $i + 1 ?>&type=-1">
                    <?= $i + 1 ?></a>
                <?php
            }
        } else {
            for ($i = 0; $i < $data['page']; $i++) {
                ?>
                <a id="page<?= $i + 1 ?>"
                   href="/index.php?c=card&a=see-all&page=<?= $i + 1 ?>"><?= $i + 1 ?></a>
                <?php
            }
        }
    ?>
    </div>
</section>