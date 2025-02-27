<?php

namespace SFabc\dataprovider;

use Exception;

class JsonProvider
{
    private string $jsonFilePath;

    public function __construct(string $jsonFilePath)
    {
        $this->jsonFilePath = $jsonFilePath;
    }

    private function loadData(): array
    {
        if (!file_exists($this->jsonFilePath)) {
            throw new Exception("Le fichier JSON n'existe pas.");
        }

        $jsonData = file_get_contents($this->jsonFilePath);
        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erreur de décodage JSON: " . json_last_error_msg());
        }

        return $data;
    }

    public function loadFamille(): array
    {
        $data = $this->loadData();
        $familles = [];

        foreach ($data['famille'] as $familleData) {
            $sousfamilles = [];
            foreach ($familleData['sousfamilles'] as $sousfamilleData) {
                $produits = array_map(fn($produitData) => $this->mapToProduit($produitData), $sousfamilleData['produits']);
                $sousfamilles[] = new SousFamille($sousfamilleData['nom'], $produits);
            }
            $familles[] = new Famille($familleData['nom'], $sousfamilles);
        }

        return $familles;
    }

    public function loadProduit(): array
    {
        $data = $this->loadData();
        $produits = [];

        foreach ($data['famille'] as $familleData) {
            foreach ($familleData['sousfamilles'] as $sousfamilleData) {
                foreach ($sousfamilleData['produits'] as $produitData) {
                    $produits[] = $this->mapToProduit($produitData);
                }
            }
        }

        return $produits;
    }

    public function getNomFamilleByProduitId(int $produitId): ?string
    {
        $data = $this->loadData();

        foreach ($data['famille'] as $familleData) {
            foreach ($familleData['sousfamilles'] as $sousfamilleData) {
                foreach ($sousfamilleData['produits'] as $produitData) {
                    if ($produitData['id'] === $produitId) {
                        return $familleData['nom'];
                    }
                }
            }
        }

        return null;
    }

    public function updateFamilleForProduit(int $produitId, string $nouvelleFamille): void
    {
        $data = $this->loadData();
        $produitTrouve = false;
        error_log("Produit ID: $produitId, Nouvelle famille: $nouvelleFamille");
        foreach ($data['famille'] as &$familleData) {
            foreach ($familleData['sousfamilles'] as &$sousfamilleData) {
                foreach ($sousfamilleData['produits'] as &$produitData) {
                    if ($produitData['id'] === $produitId) {
                        $produitTrouve = true;
                        break 3;
                    }
                }
            }
        }

        if (!$produitTrouve) {
            throw new Exception("Produit avec l'ID $produitId non trouvé.");
        }

        error_log("Produit trouvé: " . json_encode($produitData));

        foreach ($data['famille'] as &$familleData) {
            error_log("Famille: " . json_encode($familleData));
            if ($familleData['nom'] == $nouvelleFamille) {
                error_log("Nouvelle famille trouvée: " . json_encode($familleData));
                foreach ($familleData['sousfamilles'] as &$sousfamilleData) {
                    $sousfamilleData['produits'][] = $produitData;
                }
                break;
            }
        }


        error_log("Data après ajout: " . json_encode($data));


        $this->saveData($data);
    }


    public function saveCatalogue(array $familles): void
    {
        $data = $this->loadData();
        $data['famille'] = array_map(fn($famille) => $famille->toArray(), $familles);
        $this->saveData($data);
    }

    private function    saveData(array $data): void
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erreur d'encodage JSON: " . json_last_error_msg());
        }

        if (file_put_contents($this->jsonFilePath, $jsonData) === false) {
            throw new Exception("Erreur lors de l'écriture du fichier JSON.");
        }
    }

    private function mapToProduit(array $produitData): Produit
    {
        return new Produit(
            $produitData['id'],
            $produitData['nom'],
            $produitData['description1'],
            $produitData['prix'],
            $produitData['txt1'],
            $produitData['photos'],
        );
    }

    private function mapToAvis(array $avisData): Avis
    {
        return new Avis(
            $avisData['id'],
            $avisData['idProduit'],
            $avisData['user'],
            $avisData['note'],
            $avisData['ai'],
            $avisData['date']
        );
    }
}