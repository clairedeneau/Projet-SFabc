<?php

declare(strict_types=1);

namespace SFabc\controlers;

use \Exception;
use SFabc\dataprovider\Catalogue;
use SFabc\dataprovider\JsonProvider;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GestionArticlesControler extends Controler
{
    public function get(string $params): void
    {
        try {
            $jsonProvider = new JsonProvider('../data/models/catalogue.json');
            $catalogues = $jsonProvider->loadCatalogue();
            $_SESSION['catalogue'] = [];
            if (isset($_GET['index'])){
                if(count($catalogues) ==  $_GET['index']){
                    $produit = new Catalogue(
                        count($catalogues) + 1,
                        "Nouveau produit",
                        [],
                        [],
                        '',
                        [],
                        '',
                        ''
                    );

                    $catalogues[] = $produit;

                    $jsonProvider->saveCatalogue($catalogues);
                }
            }

            $catalogues = $jsonProvider->loadCatalogue();

            foreach ($catalogues as $catalogue) {
                $_SESSION['catalogue'][] = $catalogue;
            }

            $this->render('gestionarticles', [
                'catalogue' => $catalogues
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

        if (isset($_FILES["file"])) {
            $this->handleFileUpload();
            header('Location: gestionarticles');
            
        }

        $jsonProvider = new JsonProvider('../data/models/catalogue.json');
        $catalogues = $jsonProvider->loadCatalogue();
        try {
            if (isset($_POST['_method']) && $_POST['_method'] == 'PUT') {
                if (isset($_POST['id'])) {
                    $id = $_POST['id'];
                    error_log(print_r($_POST, true));
                    foreach ($catalogues as $catalogue) {
                        if ($catalogue->getId() == $id) {
                            if (isset($_POST['form_type']) && $_POST['form_type'] === 'edit_article') {
                                $nomArticle = $_POST['nom'];
                                $famille = $_POST['famille'];
                                $sousfamille = $_POST['sousFamille'];
                                $txt1 = $_POST['text1'];

                                $catalogue->setNom($nomArticle);
                                $catalogue->setFamille($famille);
                                $catalogue->setSousFamille($sousfamille);
                                $catalogue->setTxt1($txt1);

                            } elseif ($_POST['form_type'] === 'edit_description') {
                                $currentDescriptions = [];
                                $descriptions = $catalogue->getDescription1();
                                foreach ($descriptions as $index => $description) {
                                    $currentDescriptions[] = $_POST['modif_description' . $index];
                                }
                                $catalogue->setDescription1($currentDescriptions);
                            } elseif ($_POST['form_type'] === 'edit_prix') {
                                $currentPrix = [];
                                $tarifs = $catalogue->getPrix();
                                foreach ($tarifs as $index => $tarif) {
                                    $currentPrix[] = ['description' => $_POST['modif_description' . $index], 'tarif' => $_POST['modif_prix' . $index]];
                                }
                                $catalogue->setPrix($currentPrix);
                            }

                            break;
                        }
                    }
                }
            } elseif (isset($_POST['_method']) && $_POST['_method'] == 'POST') {
                if (isset($_POST['id'])) {
                    $id = $_POST['id'];
                    if ($_POST['form_type'] === 'add_description') {
                        $newDescription = $_POST['new_description'];
                        foreach ($catalogues as $catalogue) {
                            if ($catalogue->getId() == $id) {
                                $currentDescriptions = $catalogue->getDescription1();
                                $currentDescriptions[] = $newDescription;
                                $catalogue->setDescription1($currentDescriptions);
                                break;
                            }
                        }
                    } elseif ($_POST['form_type'] === 'add_prix') {
                        $newDescription = $_POST['new_description'];
                        $newPrix = $_POST['new_prix'];
                        foreach ($catalogues as $catalogue) {
                            if ($catalogue->getId() == $id) {
                                $currentPrix = $catalogue->getPrix();
                                $currentPrix[] = ['description' => $newDescription, 'tarif' => $newPrix];
                                $catalogue->setPrix($currentPrix);
                                break;
                            }
                        }
                    }
                }
            } elseif (isset($_POST['_method']) && $_POST['_method'] == 'DELETE') {
                if (isset($_POST['id'])) {
                    $id = $_POST['id'];
                    foreach ($catalogues as $index => $catalogue) {
                        if ($catalogue->getId() == $id) {
                            unset($catalogues[$index]);
                            break;
                        }
                    }
                }
            }

            $jsonProvider->saveCatalogue($catalogues);
            $message = 'Les modifications ont été enregistrées avec succès.';
            error_log($message);
            $this->redirectTo('gestionarticles');
        } catch (Exception $e) {
            echo "Erreur: " . htmlspecialchars($e->getMessage());
            error_log("Erreur lors de la mise à jour du catalogue: " . $e->getMessage());
        }
    }

    private function handleFileUpload(): void
    {
        $fileTmpPath = $_FILES["file"]["tmp_name"];
        $fileName = $_FILES["file"]["name"];
        $fileType = $_FILES["file"]["type"];

        error_log("Fichier reçu: " . $fileName);
        error_log("Type de fichier: " . $fileType);
        $allowedTypes = ["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"];

        $destinationPath =  '../data/' . $fileName;

        if (move_uploaded_file($fileTmpPath, $destinationPath)) {
            if (in_array($fileType, $allowedTypes)) {
                $outputFileName = __DIR__ . '/../../data/models/catalogue.json';
                error_log("Fichier déplacé avec succès.");
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

        $data = [];
        $firstRow = true;
        $productCounter = 1;

        foreach ($sheetData as $row) {
            if ($firstRow) {
                $firstRow = false;
                continue;
            }

            $produit = [
                "id" => $productCounter++,
                "nom" => $row['C'],
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

            $produit["txt1"] = $row['G'];
            $produit["photos"] = isset($row['H']) ? explode(';', $row['H']) : [];
            $produit["famille"] = $row['A'];
            $produit["sousfamille"] = $row['B'];

            $data[] = $produit;
        }

        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (file_put_contents($outputFileName, $jsonData) === false) {
            error_log("Erreur lors de l'écriture du fichier JSON.");
        } else {
            error_log("Fichier JSON mis à jour avec succès.");
        }
    }
}