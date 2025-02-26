<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="static/avis.css">
    <link rel="stylesheet" href="static/header.css">
    <link rel="stylesheet" href="static/footer.css">
    <title>Avis article</title>

</head>
<header>
    <nav id="topnav">
        <div class="search-container">
            <span class="material-symbols-outlined">search</span>
            <input type="search" name="Rechercher" id="rechercher" placeholder="Rechercher">
        </div>
        <ul>
            <li><a href="/articles">Articles</a></li>
            <li><a href="/a-propos">À propos</a></li>
            <li><a href="/contact">Contact</a></li>
        </ul>
    </nav>

    <nav id="mainnav">
        <div class="logo-container">
            <a id="logo" href="/"><img src="static/images/logo.png" alt="SFabc" width="120" heigt="auto"></a>
        </div>
    </nav>
</header>
<section class="bandeau">
    <h1>Avis de l'article choisi</h1>
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>

<body>
    <div class="titre">
        <h2>Thermos personnalisé</h2>
        <p>☆ ☆ ☆ ☆ ☆</p>
    </div>
    <div class="liste-avis">
        <div class="avis">
            <div class="titre-avis">
                <h3>Pierre-Antoine G. </h3>
                <p>☆ ☆ ☆ ☆ ☆</p>
            </div>
            <div class="commentaire">
                <p>Très pratique, gravure impeccable, je recommande !!</p>
            </div>
        </div>
        <div class="avis">
            <div class="titre-avis">
                <h3>Julie C. </h3>
                <p>☆ ☆ ☆ ☆</p>
            </div>
            <div class="commentaire">
                <p>Nickel, livraison rapide</p>
            </div>
        </div>
        <div class="avis">
            <div class="titre-avis">
                <h3>Eugène H. </h3>
                <p>☆ ☆ ☆ ☆ ☆</p>
            </div>
            <div class="commentaire">
                <p></p>
            </div>
        </div>

    </div>
    <p class="retour">&#10094; <a href="">Retour</a></p>
</body>
<?php
require_once "footer.php"
?>

</html>