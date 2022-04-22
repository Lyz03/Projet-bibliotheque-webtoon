<?php
$card = $data['card'];
$userRating = $data['userRating'];
?>
<section class="card_info">
    <div>
        <?php
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getRole() === 'admin') {
                ?>
                <a class="right" href="/index.php?c=card&a=update-page&id=<?= $card->getId() ?>"><i class="fas fa-edit"></i></a>
                <a class="right" href=""><i class="fas fa-trash"></i></a>
                <?php
            }
        }
        ?>

        <h1><?= $card->getTitle() ?></h1>

        <div class="flex">
            <div class="card" style="background-image: url('/assets/images/<?= $card->getImage() ?>')"></div>

            <div id="text">
                <div>
                    <?php
                        foreach (explode(',', $card->getType()) as $value) {
                        ?>
                            <span class="kind"><?= $value ?></span>
                        <?php
                        }
                    ?>
                    <p>scenario : <span><?= $card->getScript() ?></span> dessin : <span><?= $card->getDrawing() ?></span></p>
                    <p>de : <span><?= $card->getDateStart() ?></span> à : <?php
                        if ($card->getDateEnd() === 0) {
                            echo '<span>En cours de publication</span>';
                        } else {
                            echo '<span>' . $card->getDateEnd() . '</span>';
                        }
                    ?>
                    </p>
                    <p>synopsis :
                        <span>
                           <?= $card->getSynopsis() ?>
                        </span>
                    </p>
                    <?php
                    if ($data['rating'] === null) {
                    ?>
                        <p>notes : <span>aucun avis pour le moment</span></p>
                    <?php
                    } else {
                    ?>
                        <p>notes : <span><?= round($data['rating'], 2) ?> / 3</span></p>
                    <?php
                    }

                    if (isset($_SESSION['user'])) {
                        if ($userRating === null) {
                        ?>
                            <a class="star" href=""><i class="far fa-star"></i></a>
                            <a class="star" href=""><i class="far fa-star"></i></a>
                            <a class="star" href=""><i class="far fa-star"></i></a>
                        <?php
                        } else {
                            for ($i = 1; $i <= $userRating; $i++) {
                            ?>
                                <a class="star" href=""><i class="fas fa-star"></i></a>
                            <?php
                            }

                            $total = 3 - $userRating;

                            if ($total > 0) {
                                for ($i = 1; $i <= $total; $i++) {
                                ?>
                                    <a class="star" href="/index.php?c=card&a=add-review&id=<?= $card->getId() ?>"><i class="far fa-star"></i></a>
                                <?php
                                }
                            }
                        }
                    ?>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>
</section>