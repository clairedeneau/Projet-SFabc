<?php

namespace SFabc\dataprovider;

class Famille {
    private string $nom;
    private array $sousfamille;

    public function __construct(string $nom, array $sousfamille) {
        $this->nom = $nom;
        $this->sousfamille = $sousfamille;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getSousFamille() {
        return $this->sousfamille;
    }

    public function setNom($nom){
        $this->nom = $nom;
    }

    public function setSousFamille($sousfamille){
        $this->sousfamille = $sousfamille;
    }

    public function addSousFamille($sousfamille){
        $this->sousfamille[] = $sousfamille;
    }

    public function addNom($nom){
        $this->nom[] = $nom;
    }

    public function removeSousFamille($sousfamille){
        $key = array_search($sousfamille, $this->sousfamille);
        if($key !== false){
            unset($this->sousfamille[$key]);
        }
    }

    public function toArray(): array {
        return [
            'nom' => $this->nom,
            'sousfamille' => $this->sousfamille
        ];
    }
}