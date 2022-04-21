<h1>Modifier / Ajouter une fiche</h1>

<div class="center-content">
    <form action="/index.php?c=card&a=update-card" method="post" id="update_card" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Titre">
        <input type="text" name="script" placeholder="Scénario">
        <input type="text" name="drawing" placeholder="Dessin">
        <label for="dateStart">Année de début de publication :</label>
        <input type="number" name="dateStart" id="dateStart">
        <label for="dateEnd">Année de fin de publication :</label>
        <input type="number" name="dateEnd" id="dateEnd">
        <textarea name="synopsis" cols="50" rows="10" placeholder="Synopsis"></textarea>
        <div>
            <select name="type">
                <?php

                use App\Config;

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

</div>

