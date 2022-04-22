<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>AnnLio | Webtoon Library</title>
</head>
<body>
<?php
if (isset($_SESSION['error'])) {
    ?>
    <div class="error">
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
}
?>
<nav>
    <form action="">
        <input type="search" placeholder="Entrez votre recherche" name="search">
        <button type="submit" name="searchSubmit"><i class="fas fa-search"></i></button>
    </form>

    <span class="menu"><i class="fas fa-bars"></i></span>
    <div class="menu">
        <ul>
            <li><a href="/index.php?c=home">Home</a></li>
            <?php
            if (isset($_SESSION['user'])) {?>
                <a href="/index.php?c=user">Compte</a>
            <?php } else{?>
                <a href="/index.php?c=connection"">Connexion</a>
            <?php }?>
            <li><a href="/index.php?c=card">Explorer</a></li>
        </ul>
    </div>
</nav>

<main>
    <?= $page ?>
</main>

<footer>
</footer>
<script src="https://kit.fontawesome.com/25d98733ec.js" crossorigin="anonymous"></script>
<script src="/assets/js/app.js"></script>
</body>
</html>
