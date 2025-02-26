<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="footer.css">
    <title>Connexion</title>
</head>
<header>
    <nav id="topnav">
        <ul>
            <li><a href="#">Retour au site en tant que visiteur</a></li>
        </ul>
    </nav>

    <nav id="mainnav">
        <div class="logo-container">
            <a id="logo" href="index.php"><img src="images/logo.png" alt="SFabc" width="120" heigt="auto"></a>
        </div>
    </nav>
</header>
<section class="bandeau">
    <h1>Accès administrateur</h1>
    <img src="images/fond_bandeau.png" alt="bandeau">
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
<footer>
    <div class="footer-content">
        <p>SFabc</p>
        <p>123 rue lorem ipsum,</p>
        <p>45000 Orléans</p>
        <p>06.02.03.04.05</p>
        <div class="social-icons">
            <a href="#"><img src="images/insta.png" alt="Instagram"></a>
            <a href="#"><img src="images/fb.png" alt="Facebook"></a>
        </div>
    </div>
    <p class="footer-note">IUT d'Orléans - Claire Deneau, Jean-Marc Jorite, Thomas Brossier</p>
</footer>
</html>