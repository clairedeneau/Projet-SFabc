<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="static/recherche-articles.css">
    <link rel="stylesheet" href="static/header.css">
    <link rel="stylesheet" href="static/footer.css">
    <title>Résultats recherche d'articles</title>
</head>
<header>
    <nav id="topnav">
        <form action="recherche-articles" method="GET" class="search-container">
            <span class="material-symbols-outlined">search</span>
            <input type="search" name="recherche" id="rechercher" placeholder="Rechercher">
            <button type="submit" style="display: none;"></button>
        </form>
        <ul>
            <li><a href="/articles" class="nav-link-active">Articles</a></li>
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
    <h1>Résultats de votre recherche</h1>
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>

<body>
    <h2>Résultats de la recherche - "<?= htmlspecialchars($query) ?>"</h2>
    <main>
        <?php if(empty($articles)): ?> 
            <p style="color: red;"> Aucun résultat trouvé !</p>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <div class="produit">
                    <?php
                        $photo = $article->getPhotos();
                        $imageSrc = !empty($photo) ? htmlspecialchars($photo[0]) : ""; 
                    ?>
                    <img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($article->getNom()) ?>" width="200" height="auto">
                    <div class="contenu-produit">
                        <h3><?= htmlspecialchars($article->getNom()) ?></h3>
                        <p id="prix">
                            <?php
                            $prix = $article->getPrix();
                            if (empty($prix)) {
                                echo "Création sur demande";
                            } elseif (count($prix) == 1) {
                                echo htmlspecialchars($prix[0]["tarif"]) . " €";
                            } else {
                                $prixMini = min(array_column($prix, 'tarif'));
                                echo "À partir de " . htmlspecialchars($prixMini) . " €";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
<?php
require_once "footer.php"
?>

</html>