<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="static/detail.css">
    <link rel="stylesheet" href="static/header.css">
    <link rel="stylesheet" href="static/footer.css">
    <title>Détails article</title>

</head>
<header>
    <nav id="topnav">
        <form action="recherche-articles" method="GET" class="search-container">
            <span class="material-symbols-outlined">search</span>
            <input type="search" name="recherche" id="rechercher" placeholder="Rechercher">
            <button type="submit" style="display: none;"></button>
        </form>
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
    <h1>Detail de l'article choisi</h1>
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>

<body>
    <h2>Thermos personnalisé</h2>
    <div class="container">
        <div class="diapo">
            <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
            <div class="slide fade">
                <img src="static/images/images_articles/gourde/gravure_gourde_personnalisee_bouteille_isotherme_thermos_infuseur_The_bambou__tasse__bois_1.jpg" alt="Image 1">
            </div>
            <div class="slide fade">
                <img src="static/images/images_articles/gourde/gravure_gourde_personnalisee_bouteille_isotherme_thermos_infuseur_The_bambou__tasse__bois_2.jpg" alt="Image 2">
            </div>
            <div class="slide fade">
                <img src="static/images/images_articles/gourde/gravure_gourde_personnalisee_bouteille_isotherme_thermos_infuseur_The_bambou__tasse__bois_3.jpg" alt="Image 3">
            </div>
            <a class="next" onclick="changeSlide(1)">&#10095;</a>
        </div>
        <script>
            let slideIndex = 0;

            function showSlides() {
                let slides = document.querySelectorAll(".slide");

                // Masquer toutes les images
                slides.forEach(slide => slide.style.display = "none");

                // Passer à l'image suivante
                slideIndex++;
                if (slideIndex > slides.length) {
                    slideIndex = 1;
                }

                // Afficher l'image actuelle
                slides[slideIndex - 1].style.display = "block";

                // Changer d’image toutes les 10 secondes
                setTimeout(showSlides, 10000);
            }

            // Changement manuel avec les boutons
            function changeSlide(n) {
                slideIndex += n;
                let slides = document.querySelectorAll(".slide");

                if (slideIndex > slides.length) {
                    slideIndex = 1;
                }
                if (slideIndex < 1) {
                    slideIndex = slides.length;
                }

                slides.forEach(slide => slide.style.display = "none");
                slides[slideIndex - 1].style.display = "block";
            }

            // Lancer le diaporama automatique
            document.addEventListener("DOMContentLoaded", showSlides);
        </script>

        <div class="info">
            <h3>Caractéristiques :</h3>
            <ul>
                <li>Matériaux : Bambou et Inox</li>
                <li>Avec un filtre à thé</li>
                <li>Capacité : 450 mL</li>
            </ul>
            <p id="prix">Prix : 44,90 €</p>

            <div class="avis">
                <p>☆ ☆ ☆ ☆ ☆ - <a href="/avis">Voir les avis</a></p>
            </div>
        </div>


    </div>


</body>
<?php
require_once "footer.php"
?>

</html>