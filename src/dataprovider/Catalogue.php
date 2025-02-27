<?php

namespace SFabc\dataprovider;

class Catalogue
{
    private int $id;
    private ?string $nom;
    private ?array $description1;
    private ?string $description2;
    private ?array $prix;
    private ?string $txt1;
    private ?string $txt2;
    private ?array $photos;
    private ?string $famille;
    private ?string $sousfamille;

    public function __construct(
        int $id,
        ?string $nom,
        ?array $description1,
        ?string $description2,
        ?array $prix,
        ?string $txt1,
        ?string $txt2,
        ?array $photos,
        ?string $famille,
        ?string $sousfamille
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description1 = $description1;
        $this->description2 = $description2;
        $this->prix = $prix;
        $this->txt1 = $txt1;
        $this->txt2 = $txt2;
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

    public function getDescription1() {
        return $this->description1;
    }

    public function getDescription2() {
        return $this->description2;
    }

    public function getPrix() {
        return $this->prix;
    }

    public function getTxt1() {
        return $this->txt1;
    }

    public function getTxt2() {
        return $this->txt2;
    }

    public function getPhotos() {
        return $this->photos;
    }

    public function getFamille() {
        return $this->famille;
    }

    public function getSousfamille() {
        return $this->sousfamille;
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
        foreach($catalogues as $article){
            if($article->getId() == $id){
                return $article;
            }
        }
        return null;
    }

    public static function getProduitByName($catalogues, $name) {
        $filteredCatalogues = array_filter($catalogues, function($catalogue) use ($name) {
            return $catalogue->getNom() === $name;
        });

        return reset($filteredCatalogues);
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
        $html .= "<p>☆ ☆ ☆ ☆ ☆ - <a href='/avis'>Voir les avis</a></p>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }
}