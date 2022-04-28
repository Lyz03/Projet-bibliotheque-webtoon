<?php

use App\Config;

$card = $data['card'];
$userRating = $data['userRating'];
$userList = $data['userList'];
?>
<section class="card_info">

    <?php
    if (isset($_SESSION['user'])) {
        ?>
        <div class="border">
            <?php
            if ($_SESSION['user']->getRole() === 'admin') {
                ?>
                <a class="right" href="/index.php?c=card&a=update-page&id=<?= $card->getId() ?>"><i
                            class="fas fa-edit"></i></a>
                <a class="right delete" href="/index.php?c=card&a=delete-card&id=<?= $card->getId() ?>"><i
                            class="fas fa-trash"></i></a>
                <?php
            }
            ?>

            <h1><?= $card->getTitle() ?></h1>

            <div class="flex">
                <div class="card" style="background-image: url('/assets/images/<?= $card->getImage() ?>')"></div>

                <div class="text">
                    <div>
                        <?php
                        foreach (explode(',', $card->getType()) as $value) {
                            ?>
                            <span class="kind"><a href=""><?= $value ?></a></span>
                            <?php
                        }
                        ?>
                        <p>scenario : <span><?= $card->getScript() ?></span> dessin :
                            <span><?= $card->getDrawing() ?></span></p>
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

                        if ($userRating === null) {
                            ?>
                            <a class="star" href="/index.php?c=card&a=add-review&id=<?= $card->getId() ?>&mark=1">
                                <i class="far fa-star"></i></a>

                            <a class="star" href="/index.php?c=card&a=add-review&id=<?= $card->getId() ?>&mark=2">
                                <i class="far fa-star"></i></a>

                            <a class="star" href="/index.php?c=card&a=add-review&id=<?= $card->getId() ?>&mark=3">
                                <i class="far fa-star"></i></a>
                            <?php
                        } else {
                            for ($i = 1; $i <= $userRating; $i++) {
                                if ($i === (int)$userRating) {
                                    ?>
                                    <a class="star" href="/index.php?c=card&a=delete-review&id=<?= $card->getId() ?>"><i
                                                class="fas fa-star"></i></a>
                                    <?php
                                } else {
                                    ?>
                                    <a class="star"
                                       href="/index.php?c=card&a=update-review&id=<?= $card->getId() ?>&mark=<?= $i ?>">
                                        <i class="fas fa-star"></i></a>
                                    <?php
                                }
                            }

                            $total = 3 - $userRating;

                            if ($total > 0) {
                                if ((int)$total === 1) {
                                    ?>
                                    <a class="star"
                                       href="/index.php?c=card&a=update-review&id=<?= $card->getId() ?>&mark=3">
                                        <i class="far fa-star"></i></a>
                                    <?php
                                } else {
                                    ?>
                                    <a class="star"
                                       href="/index.php?c=card&a=update-review&id=<?= $card->getId() ?>&mark=2">
                                        <i class="far fa-star"></i></a>
                                    <a class="star"
                                       href="/index.php?c=card&a=update-review&id=<?= $card->getId() ?>&mark=3">
                                        <i class="far fa-star"></i></a>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>

                    <div class="center">
                        <div class="highlight">
                            <button class="show_list">Ajouter à une liste</button>
                        </div>
                        <div class="webtoon_list">
                            <?php
                            if ($userList === null) {
                                foreach (Config::DEFAULT_LIST as $key => $value) {
                                    ?>
                                    <a href="/index.php?c=card&a=add-list&id=<?= $card->getId() ?>&list=<?= $key ?>">
                                        <?= $value ?>
                                    </a>
                                    <?php
                                }
                            } else {
                                $array = [];
                                foreach (Config::DEFAULT_LIST as $key => $value) {
                                    foreach ($userList as $item) {
                                        if ($item->getName() === $value) {
                                            $array[] = $item->getName();
                                            ?>
                                            <a style="color: var(--mainColor3)"
                                               href="/index.php?c=card&a=remove-list&id=<?= $card->getId() ?>&list=<?= $key ?>">
                                                <?= $value ?>
                                            </a>
                                            <?php
                                        }
                                    }
                                    if (!in_array($value, $array)) {
                                        ?>
                                        <a href="/index.php?c=card&a=add-list&id=<?= $card->getId() ?>&list=<?= $key ?>">
                                            <?= $value ?>
                                        </a>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="center">
            <span class="margin">
                Un administrateur, doit valider votre commentaire pour que tout le monde puisse le voir,
                l'administrateur se réserve le droit de supprimer le commentaire.
            </span>

        <form action="/index.php?c=card&a=add-comment&id=<?= $card->getId() ?>" method="post">
            <textarea name="content" cols="30" rows="10" placeholder="Ajouter un commentaire ..."></textarea>
            <input type="submit" name="submit">
        </form>
    </div>
    <?php
        foreach ($data['comments'] as $value) {
            if ($_SESSION['user']->getId() === $value->getUser()->getId()) {
            ?>
                <div>
                     <a class="username" href="/index.php?c=user&a=user-profile&id=<?= $value->getUser()->getId() ?>">
                        <?= $value->getUser()->getUsername() ?>
                     </a>
                    <p><?= html_entity_decode($value->getContent()) ?></p>
                    <a class="delete" href="/index.php?c=card&a=delete-comment&id=<?= $value->getId() ?>&card=<?= $card->getId() ?>">supprimer</a>
                </div>
            <?php
            } else {
            ?>
                <div>
                     <a class="username" href="/index.php?c=user&a=user-profile&id=<?= $value->getUser()->getId() ?>">
                        <?= $value->getUser()->getUsername() ?>
                     </a>
                    <p><?= html_entity_decode($value->getContent()) ?></p>
                </div>
            <?php
            }
        }
    } else {
    ?>
        <div class="border">
            <h1><?= $card->getTitle() ?></h1>

            <div class="flex">
                <div class="card" style="background-image: url('/assets/images/<?= $card->getImage() ?>')"></div>

                <div class="text">
                    <div>
                        <?php
                        foreach (explode(',', $card->getType()) as $value) {
                            ?>
                            <span class="kind"><a href=""><?= $value ?></a></span>
                            <?php
                        }
                        ?>
                        <p>scenario : <span><?= $card->getScript() ?></span> dessin :
                            <span><?= $card->getDrawing() ?></span></p>
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
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
        foreach ($data['comments'] as $value) {
            ?>
            <div>
                <a class="username" href="/index.php?c=user&a=user-profile&id=<?= $value->getUser()->getId() ?>">
                    <?= $value->getUser()->getUsername() ?>
                </a>
                <p><?= html_entity_decode($value->getContent()) ?></p>
            </div>
            <?php
        }
    }
    ?>