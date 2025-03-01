<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="static/a-propos.css">
    <link rel="stylesheet" href="static/footer.css">
    <link rel="stylesheet" href="static/header.css">
    <title>À propos</title>
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
            <li><a href="/a-propos" class="nav-link-active">À propos</a></li>
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
    <h1>Le surcyclage, c'est quoi ?</h1>
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>
<body>
    <main>
        <h2>Principes du surcyclage</h2>
        <p>L’idée du surcyclage, ou de l’upcycling (en anglais) est de récupérer des matériaux, des objets qui ne servent plus afin de leurs donner une deuxième vie (ou une 3ème). La plupart de temps, cette nouvelle vie, plus haut de gamme, sera détournée de sa fonction initiale.</p>
        <h2>Bénéfices du surcyclage</h2>
        <p>L’énergie grise est l’énergie nécessaire, l’énergie consommée lors du cycle de vie du produit : de l’extraction de la matière première à son recyclage, en passant par sa fabrication, distribution, entretien… </p>
        <p>Si le but n’est pas de décrire les ACV (Analyse de Cycle de Vie) des produits, des appareils il est intéressant de comparer le recyclage qui nécessite de l’énergie grise pour transformer les produits en matières premières, et le surcyclage permet d’économiser l’énergie ET les matières premières tout en créant de la valeur avec des déchets.</p>
        <h2>L’« UPcycling » est donc un recyclage par le haut</h2>
        <p>Le surcyclage s’inscrit dans l’économie circulaire : ce qui est un déchet pour certains est une ressource pour d’autres. Enfin, cela permet de désengorger la planète des déchets.</p> 
        <p>Son impact est donc positif pour l’environnement.</p>
        <h2>Étapes du surcyclage</h2>
        <p>De nombreux matériaux se prêtent au surcyclage. Leur revalorisation va toutefois passer par plusieurs étapes :</p>
        <ul class="info-surcyclage">
            <li>la récupération de la matière première nécessite du temps</li>
            <li>la préparation exige souvent beaucoup de temps pour nettoyer avec soin, remettre en état…</li>
            <li>enfin, la création.</li>
        </ul>
    </main>
</body>
<?php
    require_once "footer.php"
?>
</html>