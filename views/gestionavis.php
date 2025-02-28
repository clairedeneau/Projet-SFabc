<?php

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="static/footer.css">
    <link rel="stylesheet" href="static/header.css">
    <link rel="stylesheet" href="static/avis.css">
    <link rel="stylesheet" href="static/gestionAvis.css">

    <title>Gestionnaire des Avis</title>
</head>
<header>
    <nav id="topnav">
        <ul>
            <li><a href="/bienvenue">Informations générales</a></li>
            <!--<li><a href="/admin">Gestionnaire des pages</a></li>-->
            <li><a href="/gestionarticles">Gestionnaire des articles</a></li>
            <li><a href="/gestionavis" class="nav-link-active">Avis</a></li>
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
    <h1>Gestionnaire des avis</h1>
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>

<body>
    <div class="content-wrapper">
        <div class="containerArticles">
                <?php
                    if(isset($_SESSION['avis']) && !empty($_SESSION['avis'])) {
                        
                        if(isset($_SESSION['catalogue'])){
                            foreach ($_SESSION['avis'] as $avis) {

                                echo "<div class='avis'>";
                                    echo "<div class='titre-avis'>";
                                        echo "<div class='titre'>";
                                            echo "<h3>" . htmlspecialchars($avis->getUser()) . "</h3>";
                                            echo "<p>";
                                            for ($i = 0; $i < $avis->getNote(); $i++) {
                                                echo "☆ ";
                                            }
                                            echo "</p>";
                                        echo "</div>";
                                        echo '<form action="gestionavis" method="post" style="display:inline; margin-left: 10px;">';
                                        echo '<input type="hidden" name="_method" value="DELETE">';
                                        echo '<input type="hidden" name="id" value="' . $avis->getId() . '">';
                                        echo '<button type="submit" class="supprimer">🗑</button>';
                                        echo '</form>';
                                    echo "</div>";
                                    echo "<div class='commentaire'>";
                                        echo "<p>" . htmlspecialchars($avis->getCommentaire()) . "</p>";
                                    echo "</div>";
                                    echo "<div class='articleNom'>";
                                        echo "<p>" . htmlspecialchars($articleNames[$avis->getId()]) . "</p>";
                                    echo "</div>";
                                echo "</div>";
                            }
                        }
                    }else {
                        echo "<p>Aucun avis</p>";
                    }
                ?>
        </div>    
    </div>
</body>

<?php
    require_once "footer.php"
?>

</html>