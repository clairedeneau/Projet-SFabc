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