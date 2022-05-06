<h1>Annlio : Webtoon Library</h1>

<section>
    <h2>Définition</h2>

    <blockquote>
        <div>
            <h3>Webtoon</h3>
            <p>
                Un webtoon (웹툰) est un manhwa publié en ligne.
                Plus largement, le terme s'applique à des bandes dessinées en ligne,
                pas toujours d'origine sud-coréenne.
            </p>
        </div>

        <div>
            <h3>Manhwa</h3>
            <p>
                Manhwa (만화) est le nom donné à la bande dessinée en Corée,
                ce terme est aussi utilisé à l'étranger pour désigner la bande dessinée coréenne.
            </p>
        </div>
        <span>- <a href="https://fr.wikipedia.org/wiki/Webtoon" target="_blank">wikipedia.org</a></span>
    </blockquote>
</section>

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

        <a class="show_more" href="/index.php?c=card&a=sort-cards&page=1&sort=popular&type=-1">Voir plus ></a>
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

        <a class="show_more" href="/index.php?c=card&a=sort-cards&sort=recent&type=0">Voir plus ></a>
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