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
            <a id="logo" href="/"><img src="static/images/logo.png" alt="SFabc" width="120" heigt="auto"></a>
        </div>
    </nav>
</header>
<section class="bandeau">
    <h1>Gerer les articles</h1>
    <img src="static/images/fond_bandeau.png" alt="bandeau">
</section>

<body>
    <div class="content-wrapper">
        <div class="containerArticles">
            <h3>Articles affichés :</h3>
            <ul>
                <?php
                if (isset($_SESSION['catalogue'])) {
                    foreach ($_SESSION['catalogue'] as $catalogue) {
                        if ($catalogue->getNom() !== null) {
                            echo '<li>';
                            echo '<form action="gestionarticles" method="post" style="display:inline; margin-left: 10px;">';
                            echo '<input type="hidden" name="_method" value="DELETE">';
                            echo '<input type="hidden" name="id" value="' . $catalogue->getId() . '">';
                            echo '<button type="submit" class="supprimer">🗑</button>';
                            echo '</form>';
                            echo '<form action="gestionarticles" method="get" style="display:inline;">';
                            echo '<input type="hidden" name="index" value="' . $catalogue->getId() . '">';
                            echo '<button type="submit" style="background:none;border:none;color:black;cursor:pointer;">' . htmlspecialchars($catalogue->getNom()) . '</button>';
                            echo '</form>';
                            echo '</li>';
                        }
                    }
                }
                ?>
            </ul>
            <?php



            if (isset($_SESSION['catalogue']) && !empty($_SESSION['catalogue'])) {
                $lastElement = end($_SESSION['catalogue']);
                $lastIndex = $lastElement->getId();
                echo '<form action="gestionarticles" method="post">';
                echo '<input type="hidden" name="_method" value="POST">';
                echo '<input type="hidden" name="form_type" value="add_article">';
                echo '<button type="submit" class="ajouter" name="id" value="' . $lastIndex + 1 . '">Ajouter un produit</button>';
                echo '</form>';
            } else {
                echo '<form action="gestionarticles" method="post">';
                echo '<input type="hidden" name="_method" value="POST">';
                echo '<input type="hidden" name="form_type" value="add_article">';
                echo '<button type="submit" class="ajouter" name="id" value="1">Ajouter un produit</button>';
                echo '</form>';
            }

            ?>
        </div>
        <?php if ($selectedCatalogue): ?>
            <div id="product-details">
                <h3>Détails du produit</h3>
                <form action="gestionarticles" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" value="<?php echo $selectedCatalogue->getId(); ?>">
                    <input type="text" name="nom" value="<?php echo htmlspecialchars($selectedCatalogue->getNom()); ?>" placeholder="Nom">
                    <select name="description_index">
                        <?php
                        $descriptions = $selectedCatalogue->getDescription1();
                        foreach ($descriptions as $index => $description) {
                            echo '<option value="' . $index . '">' . htmlspecialchars($description) . '</option>';
                        }
                        ?>
                    </select>
                    <button type="button" id="myBtn">Nouvelle Description</button>
                    <button type="button" id="myBtn2">Modifier Description</button>
                    <button type="button" name="descriptionDelete" id="myBtn7">Supprimer Description</button>

                    <?php
                    $selectedFamille = $selectedCatalogue->getFamille();
                    $selectedFamilleExists = false;

                    foreach ($familles as $famille => $sousFamilles) {
                        if ($famille == $selectedFamille) {
                            $selectedFamilleExists = true;
                            break;
                        }
                    }
                    ?>
                    <select name="famille" id="famille-select">
                        <?php if (!$selectedFamilleExists): ?>
                            <option value="" selected>Aucune famille</option>
                        <?php endif; ?>
                        <?php
                        foreach ($familles as $famille => $sousFamilles) {
                            if (!empty($famille)) {
                                $selected = ($famille == $selectedFamille) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($famille) . '" ' . $selected . ' data-sousfamilles="' . htmlspecialchars(json_encode($sousFamilles)) . '">' . htmlspecialchars($famille) . '</option>';
                            }
                        }
                        ?>
                    </select>

                   
                    <select name="sousFamille" id="sousFamille-select">
                        <?php
                        if (!$selectedFamilleExists || empty($familles[$selectedFamille])) {
                            echo '<option value="" selected>Aucune sous-famille</option>';
                        } else {
                            foreach ($familles[$selectedFamille] as $sousFamille) {
                                $selected = ($sousFamille == $selectedCatalogue->getSousFamille()) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($sousFamille) . '" ' . $selected . '>' . htmlspecialchars($sousFamille) . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <button type="button" id="addFamilleBtn">Ajouter Famille</button>
                    <button type="button" id="addSousFamilleBtn">Ajouter Sous-Famille</button>
                    <button type="button" id="deleteFamilleBtn">Supprimer Famille</button>
                    <button type="button" id="deleteSousFamilleBtn">Supprimer Sous-Famille</button>


                    <select name="prix_index" id="prix-select">
                        <?php
                        foreach ($selectedCatalogue->getPrix() as $index => $prix) {
                            echo '<option data-index="' . $index . '" value="' . htmlspecialchars($prix['description']) . '">' . htmlspecialchars($prix['description']) . ' ( ' . htmlspecialchars($prix['tarif']) . ' € )' . '</option>';
                        }
                        ?>
                    </select>
                    <button type="button" id="myBtn3">Nouveau Prix</button>
                    <button type="button" id="myBtn4">Modifier Prix</button>
                    <button type="button" id="myBtn8">Supprimer Prix</button>


                    <textarea name="text1" id="description" placeholder="Text"><?php echo htmlspecialchars($selectedCatalogue->getTxt1()); ?></textarea>
                    <button type="button" id="myBtn5">Ajouter une image</button>
                    <button type="button" id="myBtn6">Modifier les photos</button>

                    <button type="submit" class="save-button" name="form_type" value="edit_article">Enregistrer les changements</button>
                </form>




                <div id="addFamilleModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Ajouter une nouvelle famille</h2>
                        <form action="gestionarticles" method="post">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="id" value="<?php echo $selectedCatalogue->getId(); ?>">
                            <input type="hidden" name="form_type" value="add_famille">
                            <input type="text" name="new_famille" placeholder="Nouvelle famille" required>
                            <button type="submit" class="save-button">Enregistrer</button>
                        </form>
                    </div>
                </div>

                <div id="addSousFamilleModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Ajouter une nouvelle sous-famille</h2>
                        <form action="gestionarticles" method="post">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="id" value="<?php echo $selectedCatalogue->getId(); ?>">
                            <input type="hidden" name="form_type" value="add_sousfamille">
                            <select name="famille" required>
                                <?php
                                foreach ($familles as $famille => $sousFamilles) {
                                    echo '<option value="' . htmlspecialchars($famille) . '">' . htmlspecialchars($famille) . '</option>';
                                }
                                ?>
                            </select>
                            <input type="text" name="new_sousfamille" placeholder="Nouvelle sous-famille" required>
                            <button type="submit" class="save-button">Enregistrer</button>
                        </form>
                    </div>
                </div>

                <form id="delete-famille-form" action="gestionarticles" method="post" style="display:none;">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="id" value="<?php echo $selectedCatalogue->getId(); ?>">
                    <input type="hidden" name="famille" id="famille-to-delete">
                    <input type="hidden" name="form_type" value="delete_famille">
                </form>
                <form id="delete-sousfamille-form" action="gestionarticles" method="post" style="display:none;">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="id" value="<?php echo $selectedCatalogue->getId(); ?>">
                    <input type="hidden" name="sousfamille" id="sousfamille-to-delete">
                    <input type="hidden" name="form_type" value="delete_sousfamille">
                </form>


                <form id="delete-description-form" action="gestionarticles" method="post" style="display:none;">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="id" value="<?php echo $selectedCatalogue->getId(); ?>">
                    <input type="hidden" name="description_index" id="description-index-to-delete">
                    <input type="hidden" name="form_type" value="delete_description">
                </form>

                <form id="delete-prix-form" action="gestionarticles" method="post" style="display:none;">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="id" value="<?php echo $selectedCatalogue->getId(); ?>">
                    <input type="hidden" name="prix_index" id="prix-index-to-delete">
                    <input type="hidden" name="form_type" value="delete_prix">
                </form>

                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Ajouter une nouvelle description</h2>
                        <form action="gestionarticles" method="post">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="id" value="<?php echo $selectedCatalogue->getId(); ?>">
                            <input type="hidden" name="form_type" value="add_description">
                            <input type="text" name="new_description" placeholder="Nouvelle description">
                            <button type="submit" class="save-button">Enregistrer</button>
                        </form>
                    </div>
                </div>

                <div id="myModal2" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Modifier la description</h2>
                        <form action="gestionarticles" method="post">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="id" value="<?php echo $selectedCatalogue->getId(); ?>">
                            <input type="hidden" name="form_type" value="edit_description">
                            <?php
                            $descriptions = $selectedCatalogue->getDescription1();
                            foreach ($descriptions as $index => $description) {
                                echo '<input type="text" name="modif_description' . $index . '" placeholder="Nouvelle description" value="' . htmlspecialchars($description) . '">';
                            }
                            ?>
                            <button type="submit" class="save-button">Enregistrer</button>
                        </form>
                    </div>
                </div>

                <div id="myModal3" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Ajouter un Prix</h2>
                        <form action="gestionarticles" method="post">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="id" value="<?php echo $selectedCatalogue->getId(); ?>">
                            <input type="hidden" name="form_type" value="add_prix">
                            <input type="text" name="new_description" placeholder="Nouvelle description">
                            <input type="text" name="new_prix" placeholder="Nouveau tarif" required>
                            <button type="submit" class="save-button">Enregistrer</button>
                        </form>
                    </div>
                </div>


                <div id="myModal4" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Modifer les Prix</h2>
                        <form action="gestionarticles" method="post">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="id" value="<?php echo $selectedCatalogue->getId(); ?>">
                            <input type="hidden" name="form_type" value="edit_prix">
                            <ul>
                                <?php
                                $prixs = $selectedCatalogue->getPrix();
                                foreach ($prixs as $index => $prix) {
                                    echo '<li>';
                                    echo '<input type="text" name="modif_description' . $index . '" placeholder="Nouvelle description" value="' . htmlspecialchars($prix["description"]) . '">';
                                    echo '<input type="text" name="modif_prix' . $index . '" placeholder="Nouveau tarif" value="' . htmlspecialchars($prix["tarif"]) . '" required>';
                                    echo '</li>';
                                }
                                ?>
                            </ul>
                            <button type="submit" class="save-button">Enregistrer</button>
                        </form>
                    </div>
                </div>

                <div id="myModal5" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Ajouter une image</h2>
                        <form action="gestionarticles" method="post" enctype="multipart/form-data">
                            <label for="image">Choisissez une image (PNG ou JPEG) :</label>
                            <input type="hidden" name="id" value="<?php echo $selectedCatalogue->getId(); ?>">
                            <input type="hidden" name="form_type" value="add_image">
                            <input type="file" name="image" id="image" accept=".png, .jpeg, .jpg">
                            <p>Image selectionnée</p>
                            <img id="image-preview" src="#" alt="Aperçu de l'image" style="display:none; width: 100px; height: 100px;">
                            <button type="submit" name="upload_image">Télécharger l'image</button>
                        </form>
                    </div>
                </div>

                <div id="myModal6" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Modifier les photos</h2>
                        <form action="gestionarticles" method="post" enctype="multipart/form-data" id="edit-photo-form">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="id" value="<?php echo $selectedCatalogue->getId(); ?>">
                            <input type="hidden" name="form_type" value="edit_photos">
                            <input type="file" name="photo" accept=".png, .jpeg, .jpg" id="photo-input" style="display:none;">
                            <input type="hidden" name="photo_index" id="photo-index">
                            <button type="submit" class="save-button">Enregistrer</button>
                        </form>
                        <h3>Photos actuelles :</h3>
                        <div class="current-photos">
                            <?php
                            $photos = $selectedCatalogue->getPhotos();
                            foreach ($photos as $index => $photo) {
                                echo "<img src='" . (str_starts_with(htmlspecialchars($photo), "/") ? htmlspecialchars($photo) : "/" . htmlspecialchars($photo)) . "' alt='Image " . $index . "' class='photo' data-index='" . $index . "'>";
                            }
                            ?>
                        </div>
                        <p>Image selectionnée</p>
                        <img id="photo-preview" src="#" alt="Aperçu de l'image" style="display:none; width: 100px; height: 100px;">

                    </div>
                </div>

            </div>
        <?php else: ?>
            <div id="product-details" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                <h3 style="margin-bottom: 15px;">Choisissez un fichier au format .xlsx</h3>

                <form action="gestionarticles" method="post" enctype="multipart/form-data" style="display: flex; flex-direction: column; align-items: center;">
                    <input type="file" name="file" id="file" accept=".xlsx">
                    <button type="submit">
                        Mettre à jour
                    </button>
                </form>
            </div>





        <?php endif; ?>

    </div>
    <form action="gestionarticles" method="post" id="export-form">
        <button type="submit" name="export_csv" class="export-button">Exporter en CSV</button>
    </form>


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
<?php
require_once "footer.php"
?>

</html>