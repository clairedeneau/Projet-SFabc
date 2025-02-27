<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="static/bienvenue.css">
    <link rel="stylesheet" href="static/footer.css">
    <link rel="stylesheet" href="static/header.css">
    <title>À propos</title>
</head>
<header>
    <nav id="topnav">
        <ul>
            <li><a href="/admin/bienvenue" class="nav-link-active">Informations générales</a></li>
            <li><a href="/admin">Gestionnaire des pages</a></li>
            <li><a href="/admin">Gestionnaire des articles</a></li>
            <li><a href="/admin">Avis</a></li>
            <li><a href="/logout">Déconnexion</a></li>
        </ul>
    </nav>

    <nav id="mainnav">
        <div class="logo-container">
            <a id="logo" href="/"><img src="static/images/logo.png" alt="SFabc" width="120" heigt="auto"></a>
        </div>
    </nav>
</header>
<section class="bandeau">
    <h1>Bienvenue</h1>
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>
<body>
    <div class="container">
        <form action="footer" method="post" class="formulaire">
            <input type="email" name="email" id="mail" placeholder="Email">
            <input type="tel" name="tel" id="tel" placeholder="Numéro de téléphone">
            <input type="url" name="facebook" id="fb" placeholder="Lien Facebook">
            <input type="url" name="instagram" id="insta" placeholder="Lien Instagram">
            <textarea cols="25" rows="2" name="adresse" id="adresse" placeholder="Adresse"></textarea>
            <button type="submit">Modifier</button>
        </form>
    </div>
    
</body>
<?php
    require_once "footer.php"
?>
</html>