<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="static/logout.css">
    <link rel="stylesheet" href="static/header.css">
    <link rel="stylesheet" href="static/footer.css">
    <title>Déconnexion</title>
</head>
<header>
    <nav id="topnav">
        <ul>
            <li style="color:#B8A67E">_</li>
        </ul>
    </nav>

    <nav id="mainnav">
        <div class="logo-container">
            <a id="logo" href="/"><img src="static/images/logo.png" alt="SFabc" width="120" heigt="auto"></a>
        </div>
    </nav>
</header>
<section class="bandeau">
    <h1>Vous avez été déconnecté</h1>
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>
<body>
    <div class="container">
        <form action="/" method="get" class="formulaire-btn">
            <button type="submit">Retour au site visiteur</button>
        </form>
        <form action="admin" method="get" class="formulaire-btn">
            <button type="submit">Se reconnecter</button>
        </form>
    </div>
    
</body>
<?php
require_once "footer.php"
?>
</html>