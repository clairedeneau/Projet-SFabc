<?php
namespace SFabc\dataprovider;

use SFabc\dataprovider\Catalogue;
class JsonProvider
{
    private string $catalogueFilePath;
    private string $avisFilePath;

    public function __construct(string $catalogueFilePath, string $avisFilePath)
    {
        $this->catalogueFilePath = $catalogueFilePath;
        $this->avisFilePath = $avisFilePath;
    }

    public function loadCatalogue(): array
    {
        if (!file_exists($this->catalogueFilePath)) {
            throw new \Exception("Le fichier JSON n'existe pas.");
        }

        $jsonData = file_get_contents($this->catalogueFilePath);
        $data = json_decode($jsonData, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erreur de dÃ©codage JSON: " . json_last_error_msg());
        }

        $catalogues = [];
        foreach ($data as $catalogueData) {
            $catalogues[] = $this->mapToCatalogue($catalogueData);
        }
        return $catalogues;
    }



    private function mapToCatalogue(array $catalogueData): Catalogue
    {
        return new Catalogue(
            $catalogueData['id'],
            $catalogueData['nom'],
            $catalogueData['description1'],
            $catalogueData['description2'],
            $catalogueData['prix'],
            $catalogueData['txt1'],
            $catalogueData['txt2'],
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
            $avisData["idProduit"],
            $avisData["user"],
            intval($avisData["note"]),
            $avisData["comment"],
            $avisData["date"]
        );
    }


}