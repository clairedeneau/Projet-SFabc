<?php
namespace SFabc\dataprovider;


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
            throw new \Exception("Erreur de décodage JSON: " . json_last_error_msg());
        }

        $catalogues = [];
        foreach ($data as $catalogueData) {
            $catalogues[] = $this->mapToCatalogue($catalogueData);
        }
        return $catalogues;
    }

    public function saveCatalogue(array $catalogues)
    {

        $_SESSION["savedCatalogue"] = $catalogues;
        $data = [];
        foreach ($catalogues as $catalogue) {
            $data[] = $catalogue->toArray();
        }

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erreur d'encodage JSON: " . json_last_error_msg());
        }

        if (file_put_contents($this->jsonFilePath, $jsonData) === false) {
            throw new \Exception("Erreur lors de l'écriture du fichier JSON.");
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


}

