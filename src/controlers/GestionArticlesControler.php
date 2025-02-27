<?php

declare(strict_types=1);

namespace SFabc\controlers;

use \Exception;
use SFabc\dataprovider\Produit;
use SFabc\dataprovider\Famille;
use SFabc\dataprovider\SousFamille;
use SFabc\dataprovider\JsonProvider;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GestionArticlesControler extends Controler
{
    public function get(string $params): void
    {
        try {
            $jsonProvider = new JsonProvider('../data/models/catalogue.json');
            $familleData = $jsonProvider->loadFamille();
            $produitData = $jsonProvider->loadProduit();
            $selectedCatalogue = null;

            //if (isset($_GET['index'])) {
            //    error_log("New CATALOGIE");
            //    $id = $_GET['index'];
            //    foreach ($familleData as $famille) {
            //        foreach ($famille->getSousfamilles() as $sousfamille) {
            //            foreach ($sousfamille->getProduits() as $produit) {
            //                error_log("Produit::getProduitById : " . print_r($produit->getFamille(), true));
            //                if ($produit->getId() == $id) {
            //                    // Ajoutez ici le code pour traiter le produit sélectionné
            //                }
            //            }
            //        }
            //    }
            //}

            if(isset($_GET['index'])) {
                if (isset($_SESSION['produit'][$_GET['index']])) {
                    $selectedCatalogue = $_SESSION['produit'][$_GET['index'] -1];
                    $nomFamille = $this->getFamilleNameByProductId($familleData, $selectedCatalogue->getId());
                    $nomSousFamille = $this->getSousFamilleNameByProductId($familleData, $selectedCatalogue->getId());
                }
            }
            $_SESSION['famille'] = [];
            foreach ($familleData as $famille) {    
                $_SESSION['famille'][] = $famille;       
            }
            $_SESSION['produit'] = [];
            foreach ($produitData as $produit) {
                $_SESSION['produit'][] = $produit;
            }

            $catalogues =  array_map(fn($famille) => $famille->toArray(), $familleData);

            
            $this->render('gestionarticles', [
                'catalogues' => $catalogues,
                'selectedCatalogue' => $selectedCatalogue,
                'nomFamille' => $nomFamille,
                'nomSousFamille' => $nomSousFamille
                
            ]);
        } catch (Exception $e) {
            echo "Erreur: " . htmlspecialchars($e->getMessage());
            error_log("Erreur lors du chargement du catalogue: " . $e->getMessage());
        }
    }

    public function post(string $params): void
    {
        if (isset($_POST['deconnexion'])) {
            session_destroy();
            header('Location: connexionAdmin.php');
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
            $this->handleFileUpload();
        } else {
            error_log("Mise à jour du catalogue");

            $jsonProvider = new JsonProvider('../data/models/catalogue.json');
            $familleData = $jsonProvider->loadFamille();
            try {
                if (isset($_POST['_method']) && $_POST['_method'] == 'PUT') {
        
                    //$this->updateProduct($familleData);
                    if(isset($_POST['form_type']) && $_POST['form_type'] == 'edit_article') {
                        //$this->updateProductFamille($_POST['id'], $_POST['famille'], $_POST['sousfamille']);
                        $jsonProvider->updateFamilleForProduit((int)$_POST['id'], $_POST['famille']);
                    }
                } elseif (isset($_POST['_method']) && $_POST['_method'] == 'POST') {
                    $this->addProduct($familleData);
                } elseif (isset($_POST['_method']) && $_POST['_method'] == 'DELETE') {
                    $this->deleteProduct($familleData);
                }

                $jsonProvider->saveCatalogue($familleData);
                $message = 'Les modifications ont été enregistrées avec succès.';
                error_log($message);
                $this->redirectTo('gestionarticles');
            } catch (Exception $e) {
                echo "Erreur: " . htmlspecialchars($e->getMessage());
                error_log("Erreur lors de la mise à jour du catalogue: " . $e->getMessage());
            }
        }
    }

    function getFamilleNameByProductId($familles, $productId) {
        error_log("Catalogue : " . print_r($familles, true));
        foreach($familles as $famille) {
            foreach ($famille->getSousFamille() as $sousfamille) {
                error_log("Famille : " . print_r($sousfamille->getProduits(), true));
                foreach ($sousfamille->getProduits() as $produit) {
                    if ($produit->getId() == $productId) {
                        return $famille->getNom();
                    }
                }
            }
        }
        return null;
    }

    function getSousFamilleNameByProductId($familles, $productId) {
        foreach($familles as $famille) {
            foreach ($famille->getSousFamille() as $sousfamille) {
                foreach ($sousfamille->getProduits() as $produit) {
                    if ($produit->getId() == $productId) {
                        return $sousfamille->getNom();
                    }
                }
            }
        }
        return null;
    }
    


    public function updateProductFamille($productId, $newFamille, $newSousFamille): void
{
    try {
        $jsonProvider = new JsonProvider('../data/models/catalogue.json');
        $familleData = $jsonProvider->loadFamille();  // Chargement des données JSON

        $productToUpdate = null;
        $oldFamilleKey = null;
        $oldSousFamilleKey = null;
        // Parcours pour trouver le produit et le retirer de l'ancienne famille

        foreach ($familleData as $familleKey => &$famille) {
            error_log("test1");
            foreach ($famille->getSousFamille() as $sousFamilleKey => &$sousfamille) {
                foreach ($sousfamille->getProduits() as $key => &$produit) {
                    if ($produit->getId() == $productId) {
                        error_log("Produit trouvé : " . print_r($produit, true));
                        $productToUpdate = &$produit;  // On sauvegarde le produit
                        $oldFamilleKey = $familleKey; // On sauvegarde la clé de la famille
                        $oldSousFamilleKey = $sousFamilleKey; // On sauvegarde la clé de la sous-famille
                        unset($famille['sousfamilles'][$sousFamilleKey]['produits'][$key]); // On supprime le produit de l'ancienne famille
                        break 3; // Sortir des 3 boucles
                    }
                }
            }
        }

        error_log("Produit à mettre à jour : " . print_r($productToUpdate, true));

        // On vérifie que le produit existe et que la nouvelle famille est différente
        if ($productToUpdate !== null) {
            // On cherche où insérer le produit dans la nouvelle famille
            foreach ($familleData as &$famille) {
                if ($famille->getNom() == $newFamille) {
                    // On cherche la sous-famille correspondante
                    foreach ($famille->getSousFamille() as &$sousfamille) {
                        if ($sousfamille->getNom() == $newSousFamille) {
                            // On ajoute le produit à la nouvelle sous-famille
                            $productToUpdate['famille'] = $newFamille;
                            $productToUpdate['sousfamille'] = $newSousFamille;
                            $sousfamille['produits'][] = $productToUpdate;
                            break 2; // Sortir des 2 boucles après ajout
                        }
                    }
                }
            }
        }

        // Sauvegarder les modifications dans le fichier JSON
        file_put_contents('../data/models/catalogue.json', json_encode($familleData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        echo "Produit mis à jour avec succès.";
    } catch (Exception $e) {
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}


    private function updateProduct(array &$familleData): void
{
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        error_log("ID : " . $id);
        error_log("POST : " . print_r($_POST, true));
        error_log("UPDTATE UPDTATEUPDTATEUPDTATEUPDTATEUPDTATEPRODUCT");

        // Sauvegarder les données initiales
        $originalFamilleData = unserialize(serialize($familleData));

        if (isset($_POST['form_type']) && $_POST['form_type'] === 'edit_article') {
            $nomArticle = $_POST['nom'];
            $description1 = json_decode($_POST['description'], true);
            $familleNom = $_POST['famille'];
            $sousfamilleNom = $_POST['sousfamille'];
            $prix = json_decode($_POST['prixI'], true);
            $txt1 = $_POST['text1'];

            // Recherche du produit à mettre à jour
            foreach ($familleData as &$famille) { 
                // Vérification si on est dans la bonne famille
                if ($famille->getNom() == $familleNom) {
                    error_log("orere");
                    foreach ($famille->getSousFamille() as &$sousfamille) {
                    error_log("orereorereorereorereorereorereorere");

                
                            foreach ($sousfamille->getProduits() as &$produit) {
                                // Si le produit correspond à l'ID
                                error_log("Produit ID : ");
                                if ($produit->getId() == $id) {
                                    error_log("Produit avant update : " . print_r($produit, true));

                                    // Récupérer la famille et sous-famille actuelle du produit
                                    $oldFamilleNom = $famille->getNom();
                                    $oldSousFamilleNom = $sousfamille->getNom();
                                    
                                    error_log("OLD Famille : " . $oldFamilleNom);
                                    error_log("OLD SousFamille : " . $oldSousFamilleNom);
                                    error_log("NEW Famille : " . $familleNom);
                                    error_log("NEW SousFamille : " . $sousfamilleNom);
                                    // Mise à jour des propriétés du produit
                                    $produit->setNom($nomArticle);
                                    $produit->setDescription1($description1);
                                    $produit->setPrix($prix);
                                    $produit->setTxt1($txt1);

                                    // Si la famille ou la sous-famille a changé
                                    if ($oldFamilleNom != $familleNom || $oldSousFamilleNom != $sousfamilleNom) {
                                        // Supprimer le produit de l'ancienne famille
                                        $this->removeProductFromFamily($familleData, $produit, $oldFamilleNom, $oldSousFamilleNom);
                                        
                                        // Ajouter le produit à la nouvelle famille
                                        $this->addProductToFamily($familleData, $produit, $familleNom, $sousfamilleNom);
                                    }

                                    error_log("Produit après update : " . print_r($produit, true));
                                    break 3; // Arrêter les boucles une fois le produit trouvé et mis à jour
                                }
                            }
                    }
                }
            }
        }
    }
}

// Fonction pour supprimer le produit de l'ancienne famille
private function removeProductFromFamily(array &$familleData, $produit, $familleNom, $sousfamilleNom)
{
    foreach ($familleData as &$famille) {
        if ($famille->getNom() == $familleNom) {
            foreach ($famille->getSousFamille() as &$sousfamille) {
                if ($sousfamille->getNom() == $sousfamilleNom) {
                    // Retirer le produit de la sous-famille
                    foreach ($sousfamille->getProduits() as $key => &$prod) {
                        if ($prod->getId() == $produit->getId()) {
                            unset($sousfamille->getProduits()[$key]);
                            return; // Le produit est supprimé, sortir de la fonction
                        }
                    }
                }
            }
        }
    }
}

// Fonction pour ajouter le produit à la nouvelle famille
private function addProductToFamily(array &$familleData, $produit, $familleNom, $sousfamilleNom)
{
    foreach ($familleData as &$famille) {
        if ($famille->getNom() == $familleNom) {
            foreach ($famille->getSousFamille() as &$sousfamille) {
                if ($sousfamille->getNom() == $sousfamilleNom) {
                    // Ajouter le produit à la sous-famille
                    $sousfamille->getProduits()[] = $produit;
                    return; // Le produit est ajouté, sortir de la fonction
                }
            }
        }
    }
}


    private function addProduct(array &$familleData): void
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            foreach ($familleData as &$famille) {
                foreach ($famille->getSousfamilles() as &$sousfamille) {
                    foreach ($sousfamille->getProduits() as &$produit) {
                        if ($produit->getId() == $id) {
                            if ($_POST['form_type'] === 'add_description') {
                                $produit->getDescription1()[] = $_POST['new_description'];
                            } elseif ($_POST['form_type'] === 'add_prix') {
                                $produit->getPrix()[] = [
                                    'description' => $_POST['new_description'],
                                    'tarif' => $_POST['new_prix']
                                ];
                            }
                            return;
                        }
                    }
                }
            }
        }
    }

    private function deleteProduct(array &$familleData): void
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            foreach ($familleData as &$famille) {
                foreach ($famille->getSousfamilles() as &$sousfamille) {
                    foreach ($sousfamille->getProduits() as $index => $produit) {
                        if ($produit->getId() == $id) {
                            unset($sousfamille->getProduits()[$index]);
                            return;
                        }
                    }
                }
            }
        }
    }

    private function handleFileUpload(): void
    {
        $fileTmpPath = $_FILES["file"]["tmp_name"];
        $fileName = $_FILES["file"]["name"];
        $fileType = $_FILES["file"]["type"];
        
        $allowedTypes = ["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"];

        $destinationPath =  '../data/' . $fileName;

        if (move_uploaded_file($fileTmpPath, $destinationPath)) {
            if (in_array($fileType, $allowedTypes)) {
                $outputFileName = '../data/models/catalogue.json';
                $this->convertToJSON($destinationPath, $outputFileName);
            } else {
                echo "Format de fichier non supporté.";
            }
        } else {
            echo "Échec du déplacement du fichier.";
        }
    }

    private function convertToJSON(string $inputFileName, string $outputFileName): void
    {
        $spreadsheet = IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $data = ['famille' => []];
        $firstRow = true;
        $productCounter = 1;

        foreach ($sheetData as $row) {
            if ($firstRow) {
                $firstRow = false;
                continue;
            }

            $produit = [
                "id" => $productCounter++,
                "nom" => $row['C'] ?? '',
                "description1" => isset($row['D']) ? explode(';', $row['D']) : [],
                "prix" => []
            ];

            if (!empty($row['F'])) {
                $prixDescriptions = isset($row['E']) ? explode(';', $row['E']) : [];
                $prixTarifs = explode(';', str_replace(['€', ' '], '', $row['F']));

                foreach ($prixTarifs as $index => $prix) {
                    $produit["prix"][] = [
                        "description" => isset($prixDescriptions[$index]) ? trim($prixDescriptions[$index]) : '',
                        "tarif" => trim($prix)
                    ];
                }
            }

            $produit["txt1"] = $row['G'] ?? '';
            $produit["photos"] = isset($row['H']) ? explode(';', $row['H']) : [];
            $produit["famille"] = $row['A'] ?? '';
            $produit["sousfamille"] = $row['B'] ?? '';

            $familleIndex = array_search($produit["famille"], array_column($data['famille'], 'nom'));
            if ($familleIndex === false) {
                $data['famille'][] = [
                    "nom" => $produit["famille"],
                    "sousfamilles" => []
                ];
                $familleIndex = count($data['famille']) - 1;
            }

            $sousfamilleIndex = array_search($produit["sousfamille"], array_column($data['famille'][$familleIndex]['sousfamilles'], 'nom'));
            if ($sousfamilleIndex === false) {
                $data['famille'][$familleIndex]['sousfamilles'][] = [
                    "nom" => $produit["sousfamille"],
                    "produits" => []
                ];
                $sousfamilleIndex = count($data['famille'][$familleIndex]['sousfamilles']) - 1;
            }

            $data['famille'][$familleIndex]['sousfamilles'][$sousfamilleIndex]['produits'][] = $produit;
        }

        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($outputFileName, $jsonData);
    }
}