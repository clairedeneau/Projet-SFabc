<div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Ajouter une nouvelle description</h2>
                        <form action="gestionarticles" method="post">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="id" value="<?php echo $selectedCatalogue['id']; ?>">
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
                            <input type="hidden" name="id" value="<?php echo $selectedCatalogue['id']; ?>">
                            <input type="hidden" name="form_type" value="edit_description">
                            <?php 
                            $descriptions = $selectedCatalogue['description1'];
                            foreach ($descriptions as $index => $description) {
                                echo '<input type="text" name="modif_description'.$index.'" placeholder="Nouvelle description" value="' . htmlspecialchars($description) . '">';
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
                            <input type="hidden" name="id" value="<?php echo $selectedCatalogue['id']; ?>">
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
                        <h2>Modifier les Prix</h2>
                        <form action="gestionarticles" method="post">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="id" value="<?php echo $selectedCatalogue['id']; ?>">
                            <input type="hidden" name="form_type" value="edit_prix">
                            <ul>
                            <?php 
                            $prixs = $selectedCatalogue['prix'];
                            foreach ($prixs as $index => $prix) {
                                echo '<li>';
                                echo '<input type="text" name="modif_description'.$index.'" placeholder="Nouvelle description" value="' . htmlspecialchars($prix["description"]) . '">';
                                echo '<input type="text" name="modif_prix'.$index.'" placeholder="Nouveau tarif" value="' . htmlspecialchars($prix["tarif"]) . '" required>';
                                echo '</li>';  
                            }
                            ?>
                            </ul>
                            <button type="submit" class="save-button">Enregistrer</button>
                        </form>
                    </div>
                </div>