<?php
    $articles = [
        [
            "nom" => "Couteau en bois personnalisé",
            "prix" => "À partir de 15.00 €",
            "image" => "couteaux/couteau gravure personnalisée 1.jpg"
        ],
        [
            "nom" => "Eclairage ambiance bouteille - grand modèle",
            "prix" => "17.90 €",
            "image" => "bouteille/bouteille_lampe_led_gravure_personnalisee_recyclage_surcyclage_upcycling_cadeau_1.jpg"
        ],
        [
            "nom" => "Aimants bois gravés personnalisés",
            "prix" => "À partir de 15.00 €",
            "image" => "aimants/aimants gravure et découpe personnalisee bois recyclage surcyclage upcycling 1.png"
        ],
        [
            "nom" => "Jack en bois personnalisé",
            "prix" => "À partir de 26.00 €",
            "image" => "jeux/jeux_societe_jackpot_bois_recyclage_surcyclage_upcycling_palette_5.jpg"
        ]
        ];

    $query = isset($_GET['recherche']) ? strtolower(trim($_GET['recherche'])) : "";
    $articles_filtre = [];
    if($query) {
        foreach ($articles as $article) {
            if (strpos(strtolower($article["nom"]), $query) !== false) {
                $articles_filtre[] = $article;
            }
        }
    } else {
        $articles_filtre = $articles;
    }
?>


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
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>

<body>
    <h2>Résultats de la recherche - "<?= htmlspecialchars($query) ?>"</h2>
    <main>
        <?php if(empty($articles_filtre)): ?> 
            <p style="color: red;"> Aucun résultat trouvé !</p>
        <?php else: ?>
            <?php foreach ($articles_filtre as $article): ?>
                <div class="produit">
                    <img src="<?= htmlspecialchars($article["image"]) ?>" alt="<?= htmlspecialchars($article["nom"]) ?>" width="200" height="auto">
                    <div class="contenu-produit">
                        <h3><?= htmlspecialchars($article["nom"]) ?></h3>
                        <p id="prix"><?= htmlspecialchars($article["prix"]) ?></p>
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