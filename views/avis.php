<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="static/avis.css">
    <link rel="stylesheet" href="static/header.css">
    <link rel="stylesheet" href="static/footer.css">
    <title>Avis article</title>

</head>
<header>
    <nav id="topnav">
        <form action="recherche-articles" method="GET" class="search-container">
            <span class="material-symbols-outlined">search</span>
            <input type="search" name="recherche" id="rechercher" placeholder="Rechercher">
            <button type="submit" style="display: none;"></button>
        </form>
        <ul>
            <li><a href="/articles">Articles</a></li>
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
    <h1>Avis de l'article choisi</h1>
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>

<body>
    <div class="titre">
        <h2>Thermos personnalisé</h2>
        <p>☆ ☆ ☆ ☆ ☆</p>
    </div>

    <div class="formulaire-avis">
        <?php if (!empty($succes)): ?>
            <p class="succes-message"><?= htmlspecialchars($succes) ?></p>
        <?php endif; ?>
        <button class="btn-form" onclick="toggleForm()">Donner un avis</button>
        <div id="form-avis" class="form-avis">
            <form action="avis" method="POST">
                <input type="text" name="nom" id="nom" placeholder="Votre nom" required>
                <div class="note">
                    <button type="button" onclick="changeRating(-1)">-</button>
                    <input type="number" name="note" id="note" value="5" min="1" max="5" readonly>
                    <button type="button" onclick="changeRating(1)">+</button>
                </div>
                <textarea name="comment" id="comment" rows="3" placeholder="Votre commentaire ..." required></textarea>
                <button type="submit">Envoyer</button>
            </form>
        </div>
    </div>

    <div class="liste-avis">
    <?php foreach ($data as $avis): ?>
        <div class="avis">
            <div class="titre-avis">
                <h3><?= htmlspecialchars($avis['user']) ?></h3>
                <p>☆<?= str_repeat('☆', $avis['note']-1) ?></p>
            </div>
            <div class="commentaire">
                <p><?= htmlspecialchars($avis['comment']) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
    <p class="retour">&#10094; <a href="/detail">Retour</a></p>
</body>
<?php
require_once "footer.php"
?>

<script>
    function toggleForm() {
        const form = document.getElementById('form-avis');
        form.style.display = form.style.display === 'block' ? 'none' : 'block';
    }

    function changeRating(change) {
        const noteInput = document.getElementById('note');
        let currentNote = parseInt(noteInput.value);
        currentNote += change;
        if (currentNote >= 1 && currentNote <= 5) {
            noteInput.value = currentNote;
        }
    }
</script>

</html>