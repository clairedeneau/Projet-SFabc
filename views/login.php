<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="static/login.css">
    <link rel="stylesheet" href="static/header.css">
    <link rel="stylesheet" href="static/footer.css">
    <title>Connexion</title>
</head>
<header>
    <nav id="topnav">
        <ul>
            <li><a href="/">Retour au site en tant que visiteur</a></li>
        </ul>
    </nav>

    <nav id="mainnav">
        <div class="logo-container">
            <a id="logo" href="/"><img src="static/images/logo.png" alt="SFabc" width="120" heigt="auto"></a>
        </div>
    </nav>
</header>
<section class="bandeau">
    <h1>Accès administrateur</h1>
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>
<body>
    <?php if (!empty($erreur)) echo "<p id='info-erreur'>$erreur</p>"; ?>
    <div class="container">
        <form action="login.php" method="post" class="connexion-form">
            <input type="text" name="identifiant" id="identifiant" placeholder="Identifiant" required>
            <input type="password" name="password" id="mot-passe" placeholder="Mot de passe" required>
            <button type="submit" name="valider">Valider</button>
        </form>
    </div>
    
</body>
<?php
require_once "footer.php"
?>
</html>