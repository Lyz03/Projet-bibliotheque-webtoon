<form class="update_user" action="/index.php?c=connection&a=change-username" method="post">
    <h2>Nom D'utilisateur</h2>
    <input type="text" name="username" value="<?= $data['user']->getUsername() ?>" minlength="3" maxlength="45" required>
    <input type="submit" name="submit">
</form>

<form class="update_user" action="/index.php?c=connection&a=change-email" method="post">
    <h2>Email</h2>
    <input type="email" name="email" value="<?= $data['user']->getEmail() ?>" required minlength="8" maxlength="100" required>
    <input type="submit" name="submit">
</form>

<form class="update_user" action="/index.php?c=connection&a=change-password" method="post">
    <h2>Mot de passe</h2>
    <input type="password" name="oldPassword" placeholder="Ancien mot de passe" minlength="8" maxlength="255" required>
    <input type="password" name="password" placeholder="Nouveau mot de passe" minlength="8" maxlength="255" required>
    <input type="password" name="passwordRepeat" placeholder="Répétez le nouveau mot de passe" minlength="8" maxlength="255" required>
    <input type="submit" name="submit">
</form>
