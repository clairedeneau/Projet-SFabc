<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="static/contact.css">
    <link rel="stylesheet" href="static/footer.css">
    
    <title>Contact</title>
</head>
<header>
    <nav id="topnav">
        <div class="search-container">
            <span class="material-symbols-outlined">search</span>
            <input type="search" name="Rechercher" id="rechercher" placeholder="Rechercher">
        </div>
        <ul>
            <li><a href="#">Articles</a></li>
            <li><a href="#">À propos</a></li>
            <li><a href="contact.php" class="nav-link-active">Contact</a></li>
        </ul>
    </nav>

    <nav id="mainnav">
        <div class="logo-container">
            <a id="logo" href="#"><img src="static/images/logo.png" alt="SFabc" width="120" heigt="auto"></a>
        </div>
    </nav>
</header>
<section class="bandeau">
    <h1>Contactez-moi !</h1>
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>
<body>
    <p id="info-form">Les champs marqués d’un * sont obligatoires.</p>
    <div class="container">
        <form action="contact" method="post" class="contact-form">

            <input type="text" id="name" name="utilisateur" placeholder="Vos nom et prénom*" required>
            
            <input type="email" id="email" name="mail" placeholder="Votre email*" required>
            
            <input type="text" id="subject" name="sujet" placeholder="Sujet*" required>
            
            <textarea id="message" name="message" cols="40" rows="5" placeholder="Votre message ..." required></textarea>
            
            <button type="submit" name="envoyer">Envoyer</button>
        </form>
    </div>
</body>
<?php
    require_once "footer.php"
?>

</html>
