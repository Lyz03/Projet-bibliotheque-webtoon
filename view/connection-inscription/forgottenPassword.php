<h1>Mot de passe oublié</h1>

<form class="update_user" action="/index.php?c=connection&a=new-password" method="post">
    <p>Un nouveau mot de passe vous sera envoyé par email, pensez à le changer dès que possible</p>
    <input type="email" name="email" placeholder="Adresse email" minlength="8" maxlength="100" required>
    <input type="submit" name="submit">
</form>