<h1>Modifier / Ajouter une fiche</h1>

<div class="center-content">
<?php
use App\Config;

    if (isset($data['card'])) {
        $card = $data['card'];

        if ($card !== null) {
            $types = explode(',', $card->getType());
        ?>
            <form action="/index.php?c=card&a=update-card&id=<?= $card->getId() ?>" method="post" id="update_card" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Titre" value="<?= $card->getTitle() ?>">
                <input type="text" name="script" placeholder="Scénario" value="<?= $card->getScript() ?>">
                <input type="text" name="drawing" placeholder="Dessin" value="<?= $card->getDrawing() ?>">
                <label for="dateStart">Année de début de publication :</label>
                <input type="number" name="dateStart" id="dateStart" min="1900" max="2800" value="<?= $card->getDateStart() ?>">
                <label for="dateEnd">Année de fin de publication :</label>
                <?php
                    if ($card->getDateEnd() === 0) {
                       echo '<input type="number" name="dateEnd" id="dateEnd" min="1900" max="2800">';
                    } else {
                    ?>
                        <input type="number" name="dateEnd" id="dateEnd" min="1900" max="2800" value="<?= $card->getDateEnd() ?>">
                    <?php
                    }
                ?>
                <textarea name="synopsis" cols="50" rows="10" placeholder="Synopsis"><?= $card->getSynopsis() ?></textarea>
                <div>
                    <select name="type">
                        <?php
                        if (count($types) >= 1) {
                        ?>
                            <option value="<?= $types[0] ?>"><?= $types[0] ?></option>
                        <?php
                        }
                        foreach (Config::CARD_TYPE as $value) {
                            ?>
                            <option value="<?= $value ?>"><?= $value ?></option>
                            <?php
                        }
                        ?>
                    </select>

                    <select name="type2">
                        <?php
                        if (count($types) >= 2) {
                            ?>
                            <option value="<?= $types[1] ?>"><?= $types[1] ?></option>
                            <?php
                        }
                        ?>
                        <option value="none">Aucun</option>
                        <?php
                        foreach (Config::CARD_TYPE as $value) {
                            ?>
                            <option value="<?= $value ?>"><?= $value ?></option>
                            <?php
                        }
                        ?>
                    </select>

                    <select name="type3">
                        <?php
                        if (count($types) === 3) {
                            ?>
                            <option value="<?= $types[2] ?>"><?= $types[2] ?></option>
                            <?php
                        }
                        ?>
                        <option value="none">Aucun</option>
                        <?php
                        foreach (Config::CARD_TYPE as $value) {
                            ?>
                            <option value="<?= $value ?>"><?= $value ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input type="file" accept=".image/jpeg, .jpg, .png" name="image">
                    <span>Taille maximum : 2Mo</span>
                    <input type="submit" name="submit">
            </form>
    <?php
        }
    } else {
    ?>
        <form action="/index.php?c=card&a=update-card&id" method="post" id="update_card" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Titre">
            <input type="text" name="script" placeholder="Scénario">
            <input type="text" name="drawing" placeholder="Dessin">
            <label for="dateStart">Année de début de publication :</label>
            <input type="number" name="dateStart" id="dateStart" min="1900" max="2800">
            <label for="dateEnd">Année de fin de publication :</label>
            <input type="number" name="dateEnd" id="dateEnd" min="1900" max="2800">
            <textarea name="synopsis" cols="50" rows="10" placeholder="Synopsis"></textarea>
            <div>
                <select name="type">
                    <?php
                    foreach (Config::CARD_TYPE as $value) {
                        ?>
                        <option value="<?= $value ?>"><?= $value ?></option>
                        <?php
                    }
                    ?>
                </select>

                <select name="type2">
                    <option value="none">Aucun</option>
                    <?php
                    foreach (Config::CARD_TYPE as $value) {
                        ?>
                        <option value="<?= $value ?>"><?= $value ?></option>
                        <?php
                    }
                    ?>
                </select>

                <select name="type3">
                    <option value="none">Aucun</option>
                    <?php
                    foreach (Config::CARD_TYPE as $value) {
                        ?>
                        <option value="<?= $value ?>"><?= $value ?></option>
                        <?php
                    }
                    ?>
                </select>
                <input type="file" accept=".image/jpeg, .jpg, .png" name="image">
                <span>Taille maximum : 2Mo</span>
                <input type="submit" name="submit">
        </form>
    <?php
    }
?>
</div>

