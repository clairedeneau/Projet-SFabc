<?php
namespace SFabc\dataprovider;

use SFabc\dataprovider\Catalogue;
use SFabc\dataprovider\Avis;
use Exception;

class JsonProvider
{
    private string $catalogueFilePath;
    private string $avisFilePath;

    public function __construct(string $catalogueFilePath, string $avisFilePath)
    {
        $this->catalogueFilePath = $catalogueFilePath;
        $this->avisFilePath = $avisFilePath;
    }

    public function loadData(): array
    {
        if (!file_exists($this->catalogueFilePath)) {
            throw new \Exception("Le fichier JSON n'existe pas.");
        }

        $jsonData = file_get_contents($this->catalogueFilePath);
        $data = json_decode($jsonData, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erreur de dÃ©codage JSON: " . json_last_error_msg());
        }

        return $data;
    }

    public function loadCatalogue(): array
    {
        return array_map(fn($item) => $this->mapToCatalogue($item), $this->loadData());
    }

    public function saveCatalogue(array $catalogues): void
    {
        $this->saveData(array_map(fn($catalogue) => $catalogue->toArray(), $catalogues));
    }

    public function saveAvis(array $avis): void
    {
        $this->saveData(['avis' => array_map(fn($avi) => $avi->toArray(), $avis)]);
    }

    private function saveData(array $data): void
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erreur d'encodage JSON: " . json_last_error_msg());
        }

        if (file_put_contents($this->catalogueFilePath, $jsonData) === false) {
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

    public function loadAvis(?int $idProduit = null): array
    {
        if (!file_exists($this->avisFilePath)) {
            throw new \Exception("Le fichier JSON n'existe pas.");
        }

        $jsonData = file_get_contents($this->avisFilePath);
        $data = json_decode($jsonData, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erreur de dÃ©codage JSON: " . json_last_error_msg());
        }

        $avis = [];
        foreach ($data["avis"] as $avisData) {
            if(!is_null($idProduit)){
                if($idProduit === $avisData["idProduit"]){
                    $avis[] = $this->mapToAvis($avisData);
                }
            }else{
                $avis[] = $this->mapToAvis($avisData);
            }
        }
        return $avis;
    }

    private function mapToAvis(array $avisData): Avis
    {
        return new Avis(
            intval($avisData["id"]),
            $avisData["idProduit"],
            $avisData["user"],
            intval($avisData["note"]),
            $avisData["comment"],
            $avisData["date"]
        );
    }


}