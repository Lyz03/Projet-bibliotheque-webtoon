<h1>Validation de votre compte</h1>

<div class="center-content">
    <form action="/index.php?c=connection&a=validate-account&id=<?= $data['id'] ?>" method="post">
        <p>Un email vous à été envoyé</p>
        <p>Vous avez 24h pour valider votre compte, après quoi il vous faudra recommencer votre inscription.</p>
        <input type="number" name="code" placeholder="Entrer votre code">
        <input type="submit" name="submit">
    </form>
</div>
