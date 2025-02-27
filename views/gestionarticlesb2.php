<?php

$selectedCatalogue = null;
if (isset($_SESSION['catalogue'][$_GET['index']])) {
    $selectedCatalogue = $_SESSION['catalogue'][$_GET['index']];
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
            <li><a href="/admin">Gestionnaire des pages</a></li>
            <li><a href="/gestionarticles"  class="nav-link-active">Gestionnaire des articles</a></li>
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
                    foreach ($_SESSION['catalogue'] as $index => $catalogue) {
                        if ($catalogue->getNom() !== null) {
                            echo '<li>';
                            echo '<form action="gestionarticles" method="post" style="display:inline; margin-left: 10px;">';
                            echo '<input type="hidden" name="_method" value="DELETE">';
                            echo '<input type="hidden" name="id" value="' . $catalogue->getId() . '">';
                            echo '<button type="submit" class="supprimer">🗑</button>';
                            echo '</form>';
                            echo '<form action="gestionarticles" method="get" style="display:inline;">';
                            echo '<input type="hidden" name="index" value="' . $index . '">';
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
             echo '<form action="gestionarticles" method="get">';
                echo '<button type="submit" class="ajouter" name="index" value="' . $lastIndex . '">Ajouter un produit</button>';
                echo '</form>';
                }
                else {
                    echo '<form action="gestionarticles" method="get">';
                    echo '<button type="submit" class="ajouter" name="index" value="0">Ajouter un produit</button>';
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

        <input type="text" name="famille" value="<?php echo htmlspecialchars($selectedCatalogue->getFamille()); ?>" placeholder="Famille">
        <input type="text" name="sousFamille" value="<?php echo htmlspecialchars($selectedCatalogue->getSousFamille()); ?>" placeholder="Sous-famille">
        <select name="prixI" id="prix-select">
            <?php 
            foreach ($selectedCatalogue->getPrix() as $index => $prix) {
                echo '<option name="'. $index .'" value="' . htmlspecialchars($prix['description']). '">' . htmlspecialchars($prix['description']) .' ( '.htmlspecialchars($prix['tarif']).' € )'.'</option>';
            }
            ?>
        </select>
        <button type="button" id="myBtn3">Nouveau Prix</button>
        <button type="button" id="myBtn4">Modifier Prix</button>

        <textarea name="text1" id="description" placeholder="Text"><?php echo htmlspecialchars($selectedCatalogue->getTxt1()); ?></textarea>
        <button type="submit" class="save-button" name="form_type" value="edit_article">Enregistrer les changements</button>
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

    

</div>
        <?php else: ?>
            <div id="product-details">
                <h3>Sélectionnez un article pour afficher les détails</h3>
            </div>
            

            

        <?php endif; ?>
    </div>
</body>

<script defer>
    var modal = document.getElementById("myModal");
    var modal2 = document.getElementById("myModal2");
    var modal3 = document.getElementById("myModal3");
    var modal4 = document.getElementById("myModal4");
    

    var btn = document.getElementById("myBtn");
    var btn2 = document.getElementById("myBtn2");
    var btn3 = document.getElementById("myBtn3");
    var btn4 = document.getElementById("myBtn4");


    var span = document.getElementsByClassName("close")[0];
    var span2 = document.getElementsByClassName("close")[1];
    var span3 = document.getElementsByClassName("close")[2];
    var span4 = document.getElementsByClassName("close")[3];

    btn.onclick = function() {
        modal.style.display = "block";
    }

    btn2.onclick = function() {
        modal2.style.display = "block";
    }

    btn3.onclick = function() {
        modal3.style.display = "block";
    }

    btn4.onclick = function() {
        modal4.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    span2.onclick = function() {
        modal2.style.display = "none";
    }

    span3.onclick = function() {
        modal3.style.display = "none";
    }

    span4.onclick = function() {
        modal4.style.display = "none";
    }

    

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
        if (event.target == modal2) {
            modal2.style.display = "none";
        }
        if (event.target == modal3) {
            modal3.style.display = "none";
        }
        if (event.target == modal4) {
            modal4.style.display = "none";
        }
    }

</script>
<?php
    require_once "footer.php"
?>

</html>