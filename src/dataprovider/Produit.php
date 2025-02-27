<?php

namespace SFabc\dataprovider;

class Produit
{
    private int $id;
    private ?string $nom;
    private ?array $description1;
    private ?array $prix;
    private ?string $txt1;
    private ?array $photos;

    public function __construct(
        int $id,
        ?string $nom,
        ?array $description1,
        ?array $prix,
        ?string $txt1,
        ?array $photos,
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description1 = $description1;
        $this->prix = $prix;
        $this->txt1 = $txt1;
        $this->photos = $photos;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    public function getDescription1(): ?array
    {
        return $this->description1;
    }

    public function setDescription1(?array $description1): void
    {
        $this->description1 = $description1;
    }

    public function getPrix(): ?array
    {
        return $this->prix;
    }

    public function setPrix(?array $prix): void
    {
        $this->prix = $prix;
    }

    public function getTxt1(): ?string
    {
        return $this->txt1;
    }

    public function setTxt1(?string $txt1): void
    {
        $this->txt1 = $txt1;
    }

    public function getPhotos(): ?array
    {
        return $this->photos;
    }

    public static function getProduitById(array $produits, int $id): ?Produit
    {
        $filteredProduits = array_filter($produits, fn($produit) => $produit->getId() == $id);
        error_log("Produit::getProduitById : " . print_r($filteredProduits, true));
        return reset($filteredProduits) ?: null;
    }

    public static function getProduitByName(array $produits, string $name): ?Produit
    {
        $filteredProduits = array_filter($produits, fn($produit) => $produit->getNom() === $name);
        return reset($filteredProduits) ?: null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'description1' => $this->description1,
            'prix' => $this->prix,
            'txt1' => $this->txt1,
            'photos' => $this->photos,
        ];
    }

    public function renderArticle(): string
    {
        $html = "<div class='produit'>";
        $html .= "<a href='/detail/{$this->id}'>";
        $html .= "<img src='{$this->photos[0]}' alt='{$this->nom}' width='200' height='auto'>";
        $html .= "<div class='contenu-produit'>";
        $html .= "<h3>{$this->nom}</h3>";
        $prixAffiche = "---------";
        if (sizeof($this->prix) == 1) {
            $prixAffiche = $this->prix[0]["tarif"] . " €";
        } elseif (sizeof($this->prix) > 1) {
            $prixMini = $this->prix[0]["tarif"];
            foreach ($this->prix as $prixActuel) {
                if ($prixActuel["tarif"] < $prixMini) {
                    $prixMini = $prixActuel["tarif"];
                }
            }
            $prixAffiche = "A partir de " . $prixMini . " €";
        }
        $html .= "<p id='prix'>{$prixAffiche}</p>";
        $html .= "</div>";
        $html .= "</a>";
        $html .= "</div>";
        return $html;
    }

    public function renderDetail(): string
    {
        $html = "<h2>{$this->getNom()}</h2>";
        $html .= "<div class='container'>";
        $html .= "<div class='diapo'>";
        $html .= "<a class='prev' onclick='changeSlide(-1)'>&#10094;</a>";
        foreach ($this->photos as $i => $photo) {
            $html .= "<div class='slide fade'>";
            $html .= "<img src='" . (str_starts_with($photo, "/") ? $photo : "/" . $photo) . "' alt='Image {$i}'>";
            $html .= "</div>";
        }
        $html .= "<a class='next' onclick='changeSlide(1)'>&#10095;</a>";
        $html .= "</div>";
        $html .= "<div class='info'>";
        $html .= "<h3>Caractéristiques :</h3>";
        $html .= "<ul>";
        foreach ($this->description1 as $desc) {
            $html .= "<li>{$desc}</li>";
        }
        $html .= "</ul>";
        if (sizeof($this->prix) == 0) {
            $html .= "<p id='prix'>Création sur demande</p>";
        } elseif (sizeof($this->prix) == 1) {
            $html .= "<p id='prix'>Prix : {$this->prix[0]["tarif"]} €</p>";
        } else {
            $html .= "<ul>";
            foreach ($this->prix as $tarif) {
                $desc = $tarif["description"];
                $prix = $tarif["tarif"];
                $html .= "<li>{$desc} : {$prix} €</li>";
            }
            $html .= "</ul>";
        }
        $html .= "<div class='avis'>";
        $html .= "<p>☆ ☆ ☆ ☆ ☆ - <a href='/avis'>Voir les avis</a></p>";
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }
}