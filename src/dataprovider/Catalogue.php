<?php

namespace SFabc\dataprovider;

class Catalogue
{
    private int $id;
    private ?string $nom;
    private ?array $description1;
    private ?array $prix;
    private ?string $txt1;
    private ?array $photos;
    private ?string $famille;
    private ?string $sousfamille;

    private ?array $avis;

    public function __construct(
        int $id,
        ?string $nom,
        ?array $description1,
        ?array $prix,
        ?string $txt1,
        ?array $photos,
        ?string $famille,
        ?string $sousfamille
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description1 = $description1;
        $this->prix = $prix;
        $this->txt1 = $txt1;
        $this->photos = $photos;
        $this->famille = $famille;
        $this->sousfamille = $sousfamille;
    }

    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom){
        $this->nom = $nom;
    }

    public function getDescription1() {
        return $this->description1;
    }

    public function setDescription1($description1) {
        $this->description1 = $description1;

    }

    public function getPrix() {
        return $this->prix;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
    }

    public function getTxt1() {
        return $this->txt1;
    }

    public function setTxt1($txt1) {
        $this->txt1 = $txt1;
    }

    public function getPhotos() {
        return $this->photos;
    }

    public function getFamille() {
        return $this->famille;
    }

    public function setFamille($famille){
        $this->famille = $famille;
    }

    public function getSousfamille() {
        return $this->sousfamille;
    }

    public function setSousfamille($sousfamille){
        $this->sousfamille = $sousfamille;
    }

    public function setAvis(array $avis){
        $this->avis = $avis;
    }

    public static function getProduitsBySousFamille($catalogues, $sousfamille) {
        $filteredCatalogues = array_filter($catalogues, function($catalogue) use ($sousfamille) {
            return $catalogue->getSousfamille() === $sousfamille;
        });

        return $filteredCatalogues;
    }

    public static function getProduitsByFamille($catalogues, $famille) {
        $filteredCatalogues = array_filter($catalogues, function($catalogue) use ($famille) {
            return $catalogue->getFamille() === $famille;
        });

        return $filteredCatalogues;
    }

    public static function getProduitById($catalogues, $id) {
        $filteredCatalogues = array_filter($catalogues, function($catalogue) use ($id) {
            return $catalogue->getId() == $id;
        });
        error_log("Catalogue::getProduitById : " . print_r($filteredCatalogues, true));
        return reset($filteredCatalogues);

    }

    public static function getProduitByName($catalogues, $name) {
        $filteredCatalogues = array_filter($catalogues, function($catalogue) use ($name) {
            return $catalogue->getNom() === $name;
        });

        return reset($filteredCatalogues);
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'description1' => $this->description1,
            'prix' => $this->prix,
            'txt1' => $this->txt1,
            'photos' => $this->photos,
            'famille' => $this->famille,
            'sousfamille' => $this->sousfamille
        ];
    }

    public function renderArticle(): string {
        $html = "<div class='produit'>";
        $html .= "<a href='"."/detail/".$this->id."'>";
        $html .= "<img src='".$this->photos[0]."' alt='".$this->nom."' width='200' height='auto'>";
        $html .= "<div class='contenu-produit'>";
        $html .= "<h3>".$this->nom."</h3>";
        $prixAffiche = "---------";
        if(sizeof($this->prix) == 1){
            $prixAffiche = $this->prix[0]["tarif"] . " €";
        }else if(sizeof($this->prix) > 1){
            $prixMini = $this->prix[0]["tarif"];
            foreach($this->prix as $prixActuel){
                if($prixActuel["tarif"] < $prixMini){
                    $prixMini = $prixActuel["tarif"];
                }
            }
            $prixAffiche = "A partir de " . $prixMini . " €";
        }
        $html .= "<p id='prix'>".$prixAffiche."</p>";
        $html .= "</div>";
        $html .= "</a>";
        $html .= "</div>";
        return $html;
    }

    public function renderDetail(): string {
        $html = "<h2>".$this->getNom()."</h2>";
        $html .= "<div class='container'>";
        $html .= "<div class='diapo'>";
        $html .= "<a class='prev' onclick='changeSlide(-1)'>&#10094;</a>";
        foreach($this->photos as $i => $photo){
            $html .= "<div class='slide fade'>";
            $html .= "<img src='" . (str_starts_with($photo,"/") ? $photo : "/" . $photo) . "' alt='Image ".$i."'>";
            $html .= "</div>";
        }
        $html .= "<a class='next' onclick='changeSlide(1)'>&#10095;</a>";
        $html .= "</div>";
        $html .= "<div class='info'>";
        $html .= "<h3>Caractéristiques :</h3>";
        $html .= "<ul>";
        foreach($this->description1 as $desc){
            $html .= "<li>" . $desc . "</li>";
        }
        $html .= "</ul>";
        if(sizeof($this->prix) == 0){
            $html .= "<p id='prix'>Création sur demande</p>";
        }else if(sizeof($this->prix) == 1){
            $html .= "<p id='prix'>Prix : " . $this->prix[0]["tarif"] . " €";
        }else{
            $html .= "<ul>";
            foreach($this->prix as $tarif){
                $desc = $tarif["description"];
                $prix = $tarif["tarif"];
                $html .= "<li>" . $desc . " : " . $prix . " €</li>";
            }
            $html .= "</ul>";
        }
        $html .= "<div class='avis'>";
        $html .= "<div class='stars-container'>";
        if(isset($this->avis) && sizeof($this->avis) > 0){
            $html .= "<div class='stars-background'>";
            $html .= "☆☆☆☆☆";
            $html .= "</div>";
            $somme = 0;
            foreach($this->avis as $currAvis){
                $somme += $currAvis->getNote();
            }
            $html .= "<div class='stars-filled' style='width: " . (($somme / sizeof($this->avis))/5*100) . "%;'>";
            $html .= "★★★★★";
            $html .= "</div>";
            $html .= "</div>";
            $html .= "<p><a href='/avis/" . $this->getId() ."'>Voir les avis</a></p>";
        }else{
            $html .= "<div class='stars-background'>";
            $html .= "-----";
            $html .= "</div>";
            $html .= "</div>";
            $html .= "<p><a href='/avis/" . $this->getId() ."'>Ajouter un avis</a></p>";
        }
        $html .= "</div>";
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }

    public function renderAvis(): string {
        $html = "<div class='titre'>";
        $html .= "<h2>".$this->getNom()."</h2>";
            $html .= "<div class='stars-container'>";
            if(isset($this->avis) && sizeof($this->avis) > 0){
                $html .= "<div class='stars-background'>";
                $html .= "☆☆☆☆☆";
                $html .= "</div>";
                $somme = 0;
                foreach($this->avis as $currAvis){
                    $somme += $currAvis->getNote();
                }
                    $html .= "<div class='stars-filled' style='width: " . (($somme / sizeof($this->avis))/5*100) . "%;'>";
                    $html .= "★★★★★";
                    $html .= "</div>";
                
            }else{
                    $html .= "<div class='stars-background'>";
                    $html .= "Pas encore d'avis";
                    $html .= "</div>";
            }
            $html .= "</div>";
        $html .= "</div>";


        $html .= "<div class='formulaire-avis'>";
        if (!empty($succes)){
            $html .= "<p class='succes-message'>" . htmlspecialchars($succes) . "</p>";
        }
            $html .= "<button class='btn-form' onclick='toggleForm()'>Donner un avis</button>";
            $html .= "<div id='form-avis' class='form-avis'>";
                $html .= "<form action='../avis/" . $this->getId() . "' method='POST'>";
                    $html .= "<input type='text' name='nom' id='nom' placeholder='Votre nom' required>";
                    $html .= "<div class='note'>";
                        $html .= "<button type='button' onclick='changeRating(-1)'>-</button>";
                        $html .= "<input type='number' name='note' id='note' value='5' min='1' max='5' readonly>";
                        $html .= "<button type='button' onclick='changeRating(1)'>+</button>";
                    $html .= "</div>";
                    $html .= "<textarea name='comment' id='comment' rows='3' placeholder='Votre commentaire ...' required></textarea>";
                    $html .= "<button type='submit'>Envoyer</button>";
                $html .= "</form>";
            $html .= "</div>";
        $html .= "</div>";

        $html .= "<div class='liste-avis'>";
        foreach($this->avis as $currAvis){
            $html .= "<div class='avis'>";
                $html .= "<div class='titre-avis'>";
                    $html .= "<h3>" . $currAvis->getUser() . "</h3>";
                    $html .= "<div class='stars-container'>";
                        $html .= "<div class='stars-background'>";
                        $html .= "☆☆☆☆☆";
                        $html .= "</div>";
                        $html .= "<div class='stars-filled' style='width: " . (($currAvis->getNote())/5*100) . "%;'>";
                        $html .= "★★★★★";
                        $html .= "</div>";
                    $html .= "</div>";
                $html .= "</div>";
                $html .= "<div class='commentaire'>";
                    $html .= "<p>" . $currAvis->getCommentaire() . "</p>";
                $html .= "</div>";
            $html .= "</div>";
        }
        $html .= "</div>";

        $html .= "<p class='retour'>&#10094; <a href='/detail/" . $this->getId() . "'>Retour</a></p>";
        return $html;
    }
}