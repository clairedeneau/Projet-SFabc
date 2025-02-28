<?php
namespace SFabc\dataprovider;

use SFabc\dataprovider\Catalogue;
class JsonProvider
{
    private string $jsonFilePath;

    public function __construct(string $jsonFilePath)
    {
        $this->jsonFilePath = $jsonFilePath;
    }

    public function loadCatalogue(): array
    {
        if (!file_exists($this->jsonFilePath)) {
            throw new \Exception("Le fichier JSON n'existe pas.");
        }

        $jsonData = file_get_contents($this->jsonFilePath);
        $data = json_decode($jsonData, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erreur de dÃ©codage JSON: " . json_last_error_msg());
        }

<<<<<<< HEAD
        $catalogues = [];
        foreach ($data as $catalogueData) {
            $catalogues[] = $this->mapToCatalogue($catalogueData);
=======
        return $data;
    }

    public function loadCatalogue(): array
    {
        return array_map(fn($item) => $this->mapToCatalogue($item), $this->loadData());
    }

    public function loadAvis(): array
    {
        return array_map(fn($item) => $this->mapToAvis($item), $this->loadData()['avis']);
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

        if (file_put_contents($this->jsonFilePath, $jsonData) === false) {
            throw new Exception("Erreur lors de l'écriture du fichier JSON.");
>>>>>>> Liaisonv2
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

<<<<<<< HEAD
=======
    private function mapToAvis(array $avisData): Avis
    {
        return new Avis(
            $avisData['id'],
            $avisData['idProduit'],
            $avisData['user'],
            $avisData['note'],
            $avisData['comment'],
            $avisData['date']
        );
    }
}
>>>>>>> Liaisonv2

}