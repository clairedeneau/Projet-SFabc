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


}