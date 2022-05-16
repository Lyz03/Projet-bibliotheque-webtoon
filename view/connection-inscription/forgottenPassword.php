<h1>Mot de passe oublié</h1>

<?php
    if ($data['token'] !== '0') {
    ?>
        <form class="update_user" action="/index.php?c=connection&a=set-new-password" method="post">
            <h2>Mot de passe</h2>
            <input type="hidden" name="token" value="<?= $data['token'] ?>">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
            <input type="password" name="password" placeholder="Nouveau mot de passe" minlength="8" maxlength="255" required>
            <input type="password" name="passwordRepeat" placeholder="Répétez le nouveau mot de passe" minlength="8" maxlength="255" required>
            <input type="submit" name="submit" id="password_submit">
        </form>
    <?php
    } else {
    ?>
        <form class="update_user" action="/index.php?c=connection&a=new-password" method="post">
            <p>Un email vas vous être envoyé, cliquer sur le lien fourni pour accéder à la page de changement de mot de passe</p>
            <input type="email" name="email" placeholder="Adresse email" minlength="8" maxlength="100" required>
            <input type="submit" name="submit">
        </form>
    <?php
    }