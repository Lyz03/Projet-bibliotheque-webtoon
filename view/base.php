<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="shortcut icon" href="/assets/avatar/bitna.ico">
    <link rel="apple-touch-icon" href="/assets/avatar/bitna.ico">

    <link rel="stylesheet" href="/assets/css/style.css">
    <title>AnnLio | Webtoon Library</title>
</head>
<body>
<div class="delete">
    <p>Souhaitez vous réellement effectuer cette action</p>
    <button class="closeDelete">Annuler</button>
    <button class="confirm">Confirmer</button>
</div>
<?php
if (isset($_SESSION['error'])) {

    $color = $_SESSION['color'] ?? '#d35447';

    ?>
    <div class="error" style="background-color: <?= $color ?>">
        <?php
        foreach ($_SESSION['error'] as $value) {
            ?>
            <p><?= $value ?></p>
            <?php
        }
        ?>
        <button id="close">x</button>
    </div>
    <?php
    unset($_SESSION['error']);
    unset($_SESSION['color']);
}
?>
<nav>
    <div class="container">
        <form action="/index.php?c=card&a=search" method="post">
            <input type="search" placeholder="Entrez votre recherche" name="search">
            <button type="submit" name="submit"><i class="fas fa-search"></i></button>
        </form>


        <div id="suggestions"></div>
    </div>


    <span class="menu"><i class="fas fa-bars"></i></span>
    <div class="menu">
        <ul>
            <li><a href="/index.php?c=home">Home</a></li>
            <li><a href="/index.php?c=card">Explorer</a></li>
            <?php
            if (isset($_SESSION['user'])) {
                ?>
                <li><a href="/index.php?c=user" class="avatar" style="background-image: url('/assets/avatar/<?= $_SESSION['user']->getAvatar() ?>')"></a></li>
                <?php
            } else{
                ?>
                <li><a href="/index.php?c=connection">Connexion</a></li>
                <?php
            }
            ?>
        </ul>
    </div>
</nav>

<main>
    <?= $page ?>
</main>

<footer>
    <div>
        <p>Nous contacter : </p>
        <address><a href="mailto:lizoe.lallier@net-c.com">lizoe.lallier@net-c.com</a></address>
    </div>

    <a href="/index.php?c=home&a=confidentiality">Politique de confidentialité</a>
</footer>
<script src="https://kit.fontawesome.com/25d98733ec.js" crossorigin="anonymous"></script>
<script src="/assets/js/app.js"></script>
<script src="/assets/js/search.js"></script>
</body>
</html>
