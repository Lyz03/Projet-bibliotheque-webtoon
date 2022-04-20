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
<main>
    <?= $page ?>
</main>
<nav>
    <form action="">
        <input type="search" placeholder="Entrez votre recherche" name="search">
        <button type="submit" name="searchSubmit"><i class="fas fa-search"></i></button>
    </form>

    <span class="menu"><i class="fas fa-bars"></i></span>
    <div class="menu">
        <ul>
            <li><a href="?c=home">Home</a></li>
            <li><a href="?c=connection">Connexion</a></li>
            <li><a href="?c=list">Explorer</a></li>
        </ul>
    </div>
</nav>


<footer>
</footer>
<script src="https://kit.fontawesome.com/25d98733ec.js" crossorigin="anonymous"></script>
<script src="/assets/js/app.js"></script>
</body>
</html>
