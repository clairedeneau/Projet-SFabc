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

    public function setNom($nom){
        $this->nom = $nom;
    }

    public function getDescription1() {
        return $this->description1;
    }

    public function setDescription1($description1) {
        $this->description1 = $description1;
    }

    public function getDescription2() {
        return $this->description2;
    }

    public function setDescription2($description2) {
        $this->description2 = $description2;
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

    public function getTxt2() {
        return $this->txt2;
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

    public function toArray() {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'description1' => $this->description1,
            'description2' => $this->description2,
            'prix' => $this->prix,
            'txt1' => $this->txt1,
            'txt2' => $this->txt2,
            'photos' => $this->photos,
            'famille' => $this->famille,
            'sousfamille' => $this->sousfamille
        ];
    }
}