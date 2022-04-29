<h1>Connexion / Inscription</h1>

<form id="connection" action="/index.php?c=connection&a=log-in" method="post">
    <h2>Connexion</h2>
    <input type="email" placeholder="Votre email" name="email" minlength="8" maxlength="100" required>
    <input type="password" placeholder="Votre mot de passe" name="password" minlength="8" maxlength="255" required>

    <input type="submit" name="submit">
</form>

<p class="createAccount">Vous ne  possédez pas de compte créer en un <span class="createAccount">ici</span></p>

<form id="register" action="/index.php?c=connection&a=register" method="post">
    <h2>Inscription</h2>
    <input type="email" placeholder="Votre email" name="email" minlength="8" maxlength="100" required>
    <input type="text" placeholder="Votre pseudo" name="username" minlength="3" maxlength="45" required>

    <input type="password" placeholder="Votre mot de passe" name="password" minlength="8" maxlength="255" required>
    <input type="password" placeholder="Répéter votre mot de passe" name="passwordRepeat" minlength="8" maxlength="255" required>

    <input type="submit" name="submit">
</form>