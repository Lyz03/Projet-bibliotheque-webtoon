<section class="filter">
    <div>
        <select name="showCards" id="showCards">
            <option value="all">Toutes les catégories</option>
            <option value="popular">Les plus populaires</option>
            <option value="recent">Les plus récents</option>
        </select>
    </div>
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