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

<body>
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
                <a id="logo" href="/"><img src="static/images/logo.png" alt="SFabc" width="120" height="auto"></a>
            </div>
        </nav>
    </header>

    <section class="bandeau">
        <h1>Gestionnaire des avis</h1>
        <img src="static/images/fond_bandeau.png" alt="bandeau">
    </section>

    <div class="content-wrapper">
        <div class="containerArticles">
            <?php if (isset($_SESSION['avis']) && !empty($_SESSION['avis'])): ?>
                <?php if (isset($_SESSION['catalogue'])): ?>
                    <?php foreach ($_SESSION['avis'] as $avis): ?>
                        <div class="avis">
                            <div class="titre-avis">
                                <div class="titre">
                                    <h3><?= htmlspecialchars($avis->getUser()) ?></h3>
                                    <p>
                                        <?php for ($i = 0; $i < $avis->getNote(); $i++): ?>
                                            ☆
                                        <?php endfor; ?>
                                    </p>
                                </div>
                                <form action="gestionavis" method="post" style="display:inline; margin-left: 10px;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="id" value="<?= $avis->getId() ?>">
                                    <button type="submit" class="supprimer">🗑</button>
                                </form>
                            </div>
                            <div class="commentaire">
                                <p><?= htmlspecialchars($avis->getCommentaire()) ?></p>
                            </div>
                            <div class="articleNom">
                                <p><?= htmlspecialchars($articleNames[$avis->getId()]) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php else: ?>
                <p>Aucun avis</p>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once "footer.php"; ?>
</body>

</html>