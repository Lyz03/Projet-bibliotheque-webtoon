<?php
$card = $data['card'];
?>
<section class="card_info">
    <div>
        <h1><?= $card->getTitle() ?></h1>

        <div class="flex">
            <div class="card" style="background-image: url('/assets/images/<?= $card->getImage() ?>')"></div>

            <div id="text">
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
                <p>notes : <span>aucun avis pour le moment</span></p>
            </div>
        </div>

    </div>
</section>