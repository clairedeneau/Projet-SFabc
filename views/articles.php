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
                if (img.naturalHeight > img.naturalWidth + 10) {
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
    <h1>Les articles en vente</h1>
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>

<body>
    <h2>Tous les articles</h2>
    <main>
        <?php
        foreach($articles as $article){
            echo $article->renderArticle();
        }
        ?>
    </main>
</body>
<?php
require_once "footer.php"
?>

</html>