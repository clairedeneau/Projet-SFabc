<?php

namespace SFabc\dataprovider;

use SFabc\dataprovider\Catalogue;
use SFabc\dataprovider\Avis;
use Exception;

class JsonProvider
{
    private string $catalogueFilePath = "../data/models/catalogue.json";
    private string $avisFilePath = "../data/models/avis.json";
    private string $familleFilePath = "../data/models/famille.json";

    public function __construct(string $catalogueFilePath = "", string $avisFilePath = "", string $familleFilePath = "")
    {
        if(!empty($catalogueFilePath)) $this->catalogueFilePath = $catalogueFilePath;
        if(!empty($avisFilePath)) $this->avisFilePath = $avisFilePath;
        if(!empty($familleFilePath)) $this->familleFilePath = $familleFilePath;
    }

    public function loadFamilles(): array
    {
        if (!file_exists($this->familleFilePath)) {
            throw new Exception("Le fichier des familles n'existe pas.");
        }

        $famillesJson = file_get_contents($this->familleFilePath);
        $familles = json_decode($famillesJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erreur de décodage JSON: " . json_last_error_msg());
        }

        return $familles;
    }

    public function saveFamilles(array $familles): void
    {
        $famillesJson = json_encode($familles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erreur d'encodage JSON: " . json_last_error_msg());
        }

        if (file_put_contents($this->familleFilePath, $famillesJson) === false) {
            throw new Exception("Erreur lors de l'écriture du fichier JSON des familles.");
        }
    }

    public function loadData(): array
    {
        if (!file_exists($this->catalogueFilePath)) {
            throw new \Exception("Le fichier JSON n'existe pas.");
        }

        $jsonData = file_get_contents($this->catalogueFilePath);
        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erreur de décodage JSON: " . json_last_error_msg());
        }

        return $data;
    }

    public function loadCatalogue(): array
    {
        return array_map(fn($item) => $this->mapToCatalogue($item), $this->loadData());
    }

    public function saveCatalogue(array $catalogues): void
    {
        // Convertir les objets Catalogue en tableaux
        $catalogueArray = array_map(fn($catalogue) => $catalogue->toArray(), $catalogues);
        
        // Sauvegarder les données en maintenant la structure de tableau
        $this->saveData($catalogueArray, $this->catalogueFilePath);
    }

    public function saveAvis(array $avis): void
    {
        $this->saveData(['avis' => array_map(fn($avi) => $avi->toArray(), $avis)], $this->avisFilePath);
    }

    private function saveData(array $data, string $filePath): void
{
    // Encoder les données en JSON avec les options appropriées
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Erreur d'encodage JSON: " . json_last_error_msg());
    }

    // Écrire les données JSON dans le fichier spécifié
    if (file_put_contents($filePath, $jsonData) === false) {
        throw new Exception("Erreur lors de l'écriture du fichier JSON.");
    }
}

    

    private function mapToCatalogue(array $catalogueData): Catalogue
    {
        return new Catalogue(
            $catalogueData['id'],
            $catalogueData['nom'],
            $catalogueData['description1'],
            $catalogueData['prix'],
            $catalogueData['txt1'],
            $catalogueData['photos'],
            $catalogueData['famille'],
            $catalogueData['sousfamille']
        );
    }

    public function loadAvis(int $idProduit = null): array
    {
        if (!file_exists($this->avisFilePath)) {
            throw new \Exception("Le fichier JSON n'existe pas.");
        }

        $jsonData = file_get_contents($this->avisFilePath);
        $data = json_decode($jsonData, true);

        error_log("loadAvis: " . json_last_error_msg());

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erreur de décodage JSON: " . json_last_error_msg());
        }

        $avis = [];
        foreach ($data["avis"] as $avisData) {
            if (!is_null($idProduit)) {
                if ($idProduit === $avisData["idProduit"]) {
                    $avis[] = $this->mapToAvis($avisData);
                }
            } else {
                $avis[] = $this->mapToAvis($avisData);
            }
        }
        return $avis;
    }

    private function mapToAvis(array $avisData): Avis
    {
        return new Avis(
            $avisData["id"],
            $avisData["idProduit"],
            $avisData["user"],
            intval($avisData["note"]),
            $avisData["comment"],
            $avisData["date"]
        );
    }

    public function addAvis(Avis $avis, bool $updateId = true): void
    {
        $allAvis = $this->loadAvis();
        if ($updateId) {
            $avis->setId(max(array_map(fn($avi) => $avi->getId(), $allAvis)));
        }
        $allAvis[] = $avis;
        $this->saveAvis($allAvis);
    }
}