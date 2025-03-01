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
    <link rel="stylesheet" href="static/gestionArticles.css">
    <link rel="stylesheet" href="static/modal.css">
    <title>Gestionnaire des Articles</title>
</head>

<body>
    <header>
        <nav id="topnav">
            <ul>
                <li><a href="/bienvenue">Informations générales</a></li>
                <!--<li><a href="/admin">Gestionnaire des pages</a></li>-->
                <li><a href="/gestionarticles" class="nav-link-active">Gestionnaire des articles</a></li>
                <li><a href="/gestionavis">Avis</a></li>
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
        <h1>Gérer les articles</h1>
        <img src="static/images/fond_bandeau.png" alt="bandeau">
    </section>

    <div class="content-wrapper">
        <div class="containerArticles">
            <h3>Articles affichés :</h3>
            <ul>
                <?php if (isset($_SESSION['catalogue'])): ?>
                    <?php foreach ($_SESSION['catalogue'] as $catalogue): ?>
                        <?php if ($catalogue->getNom() !== null): ?>
                            <li>
                                <form action="gestionarticles" method="post" style="display:inline; margin-left: 10px;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="id" value="<?= $catalogue->getId() ?>">
                                    <button type="submit" class="supprimer">🗑</button>
                                </form>
                                <form action="gestionarticles" method="get" style="display:inline;">
                                    <input type="hidden" name="index" value="<?= $catalogue->getId() ?>">
                                    <button type="submit" style="background:none;border:none;color:black;cursor:pointer;">
                                        <?= htmlspecialchars($catalogue->getNom()) ?>
                                    </button>
                                </form>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <?php if (isset($_SESSION['catalogue']) && !empty($_SESSION['catalogue'])): ?>
                <?php $lastElement = end($_SESSION['catalogue']); ?>
                <?php $lastIndex = $lastElement->getId(); ?>
                <form action="gestionarticles" method="post">
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="form_type" value="add_article">
                    <button type="submit" class="ajouter" name="id" value="<?= $lastIndex + 1 ?>">Ajouter un produit</button>
                </form>
            <?php else: ?>
                <form action="gestionarticles" method="post">
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="form_type" value="add_article">
                    <button type="submit" class="ajouter" name="id" value="1">Ajouter un produit</button>
                </form>
            <?php endif; ?>
        </div>

        <?php if ($selectedCatalogue): ?>
            <div id="product-details">
                <h3>Détails du produit</h3>
                <form action="gestionarticles" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" value="<?= $selectedCatalogue->getId() ?>">
                    <input type="text" name="nom" value="<?= htmlspecialchars($selectedCatalogue->getNom()) ?>" placeholder="Nom">
                    <select name="description_index">
                        <?php foreach ($selectedCatalogue->getDescription1() as $index => $description): ?>
                            <option value="<?= $index ?>"><?= htmlspecialchars($description) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" id="myBtn">Nouvelle Description</button>
                    <button type="button" id="myBtn2">Modifier Description</button>
                    <button type="button" name="descriptionDelete" id="myBtn7">Supprimer Description</button>

                    <?php
                    $selectedFamille = $selectedCatalogue->getFamille();
                    $selectedFamilleExists = array_key_exists($selectedFamille, $familles);
                    ?>
                    <select name="famille" id="famille-select">
                        <?php if (!$selectedFamilleExists): ?>
                            <option value="" selected>Aucune famille</option>
                        <?php endif; ?>
                        <?php foreach ($familles as $famille => $sousFamilles): ?>
                            <?php if (!empty($famille)): ?>
                                <option value="<?= htmlspecialchars($famille) ?>" <?= $famille == $selectedFamille ? 'selected' : '' ?> data-sousfamilles="<?= htmlspecialchars(json_encode($sousFamilles)) ?>">
                                    <?= htmlspecialchars($famille) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>

                    <select name="sousFamille" id="sousFamille-select">
                        <?php if (!$selectedFamilleExists || empty($familles[$selectedFamille])): ?>
                            <option value="" selected>Aucune sous-famille</option>
                        <?php else: ?>
                            <?php foreach ($familles[$selectedFamille] as $sousFamille): ?>
                                <option value="<?= htmlspecialchars($sousFamille) ?>" <?= $sousFamille == $selectedCatalogue->getSousFamille() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($sousFamille) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <button type="button" id="addFamilleBtn">Ajouter Famille</button>
                    <button type="button" id="addSousFamilleBtn">Ajouter Sous-Famille</button>
                    <button type="button" id="deleteFamilleBtn">Supprimer Famille</button>
                    <button type="button" id="deleteSousFamilleBtn">Supprimer Sous-Famille</button>

                    <select name="prix_index" id="prix-select">
                        <?php foreach ($selectedCatalogue->getPrix() as $index => $prix): ?>
                            <option data-index="<?= $index ?>" value="<?= htmlspecialchars($prix['description']) ?>">
                                <?= htmlspecialchars($prix['description']) ?> ( <?= htmlspecialchars($prix['tarif']) ?> € )
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" id="myBtn3">Nouveau Prix</button>
                    <button type="button" id="myBtn4">Modifier Prix</button>
                    <button type="button" id="myBtn8">Supprimer Prix</button>

                    <textarea name="text1" id="description" placeholder="Text"><?= htmlspecialchars($selectedCatalogue->getTxt1()) ?></textarea>
                    <button type="button" id="myBtn5">Ajouter une image</button>
                    <button type="button" id="myBtn6">Modifier les photos</button>

                    <button type="submit" class="save-button" name="form_type" value="edit_article">Enregistrer les changements</button>
                </form>

                <?php include 'modals.php'; ?>
            </div>
        <?php else: ?>
            <div id="product-details" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                <h3 style="margin-bottom: 15px;">Choisissez un fichier au format .xlsx</h3>
                <form action="gestionarticles" method="post" enctype="multipart/form-data" style="display: flex; flex-direction: column; align-items: center;">
                    <input type="file" name="file" id="file" accept=".xlsx">
                    <button type="submit">Mettre à jour</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <form action="gestionarticles" method="post" id="export-form">
        <button type="submit" name="export_csv" class="export-button">Exporter en CSV</button>
    </form>

    <script defer src="static/js/gestionarticles.js"></script>
    <?php require_once "footer.php"; ?>
</body>
<script defer>
    document.addEventListener('DOMContentLoaded', function() {
        const familleSelect = document.getElementById('famille-select');
        const sousFamilleSelect = document.getElementById('sousFamille-select');
        const selectedSousFamille = "<?php echo $selectedCatalogue ? $selectedCatalogue->getSousFamille() : ''; ?>";

        function updateSousFamilles() {
            const selectedFamille = familleSelect.options[familleSelect.selectedIndex];
            const sousFamilles = JSON.parse(selectedFamille.getAttribute('data-sousfamilles'));

            sousFamilleSelect.innerHTML = '';

            sousFamilles.forEach(function(sousFamille) {
                const option = document.createElement('option');
                option.value = sousFamille;
                option.textContent = sousFamille;
                if (sousFamille === selectedSousFamille) {
                    option.selected = true;
                }
                sousFamilleSelect.appendChild(option);
            });
        }

        if (familleSelect) {
            familleSelect.addEventListener('change', updateSousFamilles);
            familleSelect.dispatchEvent(new Event('change'));
        }

        document.querySelectorAll('.current-photos .photo').forEach(photo => {
            photo.addEventListener('click', function() {
                const photoInput = document.getElementById('photo-input');
                const photoIndex = document.getElementById('photo-index');
                const photoPreview = document.getElementById('photo-preview');

                photoIndex.value = this.getAttribute('data-index');
                photoInput.click();

                photoInput.addEventListener('change', function() {
                    const file = photoInput.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            photoPreview.src = e.target.result;
                            photoPreview.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    }
                });
            });
        });

        document.getElementById('image').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreview = document.getElementById('image-preview');
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('deleteFamilleBtn').addEventListener('click', function() {
            const famille = familleSelect.value;
            document.getElementById('famille-to-delete').value = famille;
            document.getElementById('delete-famille-form').submit();
        });

        document.getElementById('deleteSousFamilleBtn').addEventListener('click', function() {
            const sousFamille = sousFamilleSelect.value;
            document.getElementById('sousfamille-to-delete').value = sousFamille;
            document.getElementById('delete-sousfamille-form').submit();
        });

        const modals = {
            myModal: document.getElementById("myModal"),
            myModal2: document.getElementById("myModal2"),
            myModal3: document.getElementById("myModal3"),
            myModal4: document.getElementById("myModal4"),
            myModal5: document.getElementById("myModal5"),
            myModal6: document.getElementById("myModal6"),
            addFamilleModal: document.getElementById("addFamilleModal"),
            addSousFamilleModal: document.getElementById("addSousFamilleModal")
        };

        const buttons = {
            myBtn: document.getElementById("myBtn"),
            myBtn2: document.getElementById("myBtn2"),
            myBtn3: document.getElementById("myBtn3"),
            myBtn4: document.getElementById("myBtn4"),
            myBtn5: document.getElementById("myBtn5"),
            myBtn6: document.getElementById("myBtn6"),
            myBtn7: document.getElementById("myBtn7"),
            myBtn8: document.getElementById("myBtn8"),
            addFamilleBtn: document.getElementById("addFamilleBtn"),
            addSousFamilleBtn: document.getElementById("addSousFamilleBtn")
        };

        const spans = document.getElementsByClassName("close");

        function openModal(modal) {
            modal.style.display = "block";
        }

        function closeModal(modal) {
            modal.style.display = "none";
        }

        buttons.myBtn.onclick = () => openModal(modals.myModal);
        buttons.myBtn2.onclick = () => openModal(modals.myModal2);
        buttons.myBtn3.onclick = () => openModal(modals.myModal3);
        buttons.myBtn4.onclick = () => openModal(modals.myModal4);
        buttons.myBtn5.onclick = () => openModal(modals.myModal5);
        buttons.myBtn6.onclick = () => openModal(modals.myModal6);
        buttons.addFamilleBtn.onclick = () => openModal(modals.addFamilleModal);
        buttons.addSousFamilleBtn.onclick = () => openModal(modals.addSousFamilleModal);

        Array.from(spans).forEach(span => {
            span.onclick = function() {
                Object.values(modals).forEach(closeModal);
            }
        });

        window.onclick = function(event) {
            Object.values(modals).forEach(modal => {
                if (event.target == modal) {
                    closeModal(modal);
                }
            });
        }

        buttons.myBtn7.onclick = function() {
            const descriptionIndex = document.querySelector('select[name="description_index"]').value;
            document.getElementById('description-index-to-delete').value = descriptionIndex;
            document.getElementById('delete-description-form').submit();
        }

        buttons.myBtn8.onclick = function() {
            const prixIndex = document.querySelector('#prix-select option:checked').getAttribute('data-index');
            document.getElementById('prix-index-to-delete').value = prixIndex;
            document.getElementById('delete-prix-form').submit();
        }
    });
</script>

</html>