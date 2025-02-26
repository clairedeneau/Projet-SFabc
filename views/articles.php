<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="static/articles.css">
    <link rel="stylesheet" href="static/header.css">
    <link rel="stylesheet" href="static/footer.css">
    <title>Articles</title>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".produit img").forEach(img => {
                if (img.naturalHeight > img.naturalWidth) {
                    img.style.transform = "rotate(90deg)";
                }
            });
        });
    </script>

</head>
<header>
    <nav id="topnav">
        <form action="recherche-articles" method="GET" class="search-container">
            <span class="material-symbols-outlined">search</span>
            <input type="search" name="recherche" id="rechercher" placeholder="Rechercher">
            <button type="submit" style="display: none;"></button>
        </form>
        <ul>
            <li><a href="/articles"  class="nav-link-active">Articles</a></li>
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
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>

<body>
    <h2>Tous les articles</h2>
    <main>
        <div class="produit">
            <img src="couteaux/couteau gravure personnalisée 1.jpg" alt="Couteau gravure" width="200" height="auto">
            <div class="contenu-produit">
                <h3>Couteau en bois personnalisé</h3>
                <p id="prix">À partir de 15.00 €</p>
            </div>
        </div>
        <div class="produit">
            <img src="bouteille/bouteille_lampe_led_gravure_personnalisee_recyclage_surcyclage_upcycling_cadeau_1.jpg" alt="Bouteille gravure" width="200" height="auto">
            <div class="contenu-produit">
                <h3>Eclairage ambiance bouteille - grand modèle</h3>
                <p id="prix">17.90 €</p>
            </div>
        </div>
        <div class="produit">
            <img src="aimants/aimants gravure et découpe personnalisee bois recyclage surcyclage upcycling 1.png" alt="Aimants" width="200" height="auto">
            <div class="contenu-produit">
                <h3>Aimants bois gravés personnalisés</h3>
                <p id="prix">À partir de 15.00 €</p>
            </div>
        </div>
        <div class="produit">
            <img src="jeux/jeux_societe_jackpot_bois_recyclage_surcyclage_upcycling_palette_5.jpg" alt="Jeu bois gravure" width="200" height="auto">
            <div class="contenu-produit">
                <h3>Jack en bois personnalisé</h3>
                <p id="prix">À partir de 26.00 €</p>
            </div>
        </div>
    </main>
</body>
<?php
require_once "footer.php"
?>

</html>