<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="static/accueil.css">
    <link rel="stylesheet" href="static/header.css">
    <link rel="stylesheet" href="static/footer.css">
    <title>Accueil</title>
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
            <a id="logo" href="index.php"><img src="static/images/logo.png" alt="SFabc" width="120" heigt="auto"></a>
        </div>
    </nav>
</header>
<section class="bandeau">
    <h1>Accueil</h1>
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>
<body>
    <main>
        <h2>Découvrez mes créations</h2>
        <div class="produit">
            <img src="static/images/images_articles/couteaux/couteau gravure personnalisée 1.jpg" alt="Couteau gravure" width="200" height="auto">
            <div class="contenu-produit">
                <h3>Couteau en bois personnalisé</h3>
                <p>Couteau pliant avec gravure personnalisée.</p>
                <p>Deux zones de gravures.</p>
                <p>À partir de 15.00 €</p>
            </div>
        </div>
        <div class="separateur"></div>
        <div class="produit">
            <img src="static/images/images_articles/bouteille/bouteille_lampe_led_gravure_personnalisee_recyclage_surcyclage_upcycling_cadeau_1.jpg" alt="Bouteille gravure" width="200" height="auto">
            <div class="contenu-produit">
                <h3>Bouteille en verre personnalisée</h3>
                <p>Bouteille avec éclairage d'ambiance</p>
                <p>Gravure personnalisée sur la bouteille</p>
                <p>Deux modèles disponibles</p>
                <p>À partir de 14.90 €</p>
            </div>
        </div>
    </main>
</body>
<?php
    require_once "footer.php"
?>
</html>