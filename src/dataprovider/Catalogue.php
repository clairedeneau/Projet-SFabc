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
        $filteredCatalogues = array_filter($catalogues, function($catalogue) use ($id) {
            return $catalogue->getId() === $id;
        });

        return reset($filteredCatalogues);
    }

    public static function getProduitByName($catalogues, $name) {
        $filteredCatalogues = array_filter($catalogues, function($catalogue) use ($name) {
            return $catalogue->getNom() === $name;
        });

        return reset($filteredCatalogues);
    }

    public function renderArticle(): string {
        $html = "<div class='produit'>";
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
        $html .= "</div>";
        return $html;
    }
}