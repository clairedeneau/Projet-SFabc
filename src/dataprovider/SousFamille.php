<?php

namespace SFabc\dataprovider;

class SousFamille{
    private string $nom;
    private array $produits;

    public function __construct(string $nom, array $produits){
        $this->nom = $nom;
        $this->produits = $produits;
    }


    public function getNom(){
        return $this->nom;
    }

    public function getProduits(){
        return $this->produits;
    }

    public function setNom($nom){
        $this->nom = $nom;
    }

    public function setProduits($produits){
        $this->produits = $produits;
    }

    public function addProduit($produit){
        $this->produits[] = $produit;
    }

    public function removeProduit($produit){
        $key = array_search($produit, $this->produits);
        if($key !== false){
            unset($this->produits[$key]);
        }
    }

    public function toArray(): array {
        return [
            'nom' => $this->nom,
            'produits' => $this->produits
        ];
    }
}