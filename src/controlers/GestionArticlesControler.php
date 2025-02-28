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
            $jsonProvider = new JsonProvider('../data/models/catalogue.json', "../data/models/avis.json", '../data/models/famille.json');
            $catalogues = $jsonProvider->loadCatalogue();
            $_SESSION['catalogue'] = [];
            
            $catalogues = $jsonProvider->loadCatalogue();        
            $familles = $jsonProvider->loadFamilles();

           

            foreach ($catalogues as $catalogue) {
                $_SESSION['catalogue'][] = $catalogue;
            }
            if(isset($_GET['index']) && $_SESSION['catalogue'][$_GET['index']]){
                error_log("Index: ".$_GET['index']);
                error_log("Catalogue: ". print_r($_SESSION['catalogue'], true));

            }

            foreach ($catalogues as $catalogue) {
                if (isset($_GET['index']) && $catalogue->getId() == $_GET['index']) {
                    $selectedCatalogue = $catalogue;
                    break;
                }
            }


            $this->render('gestionarticles', [
                'catalogue' => $catalogues,
                'selectedCatalogue' => $selectedCatalogue ?? null,
                'famillesJson' => json_encode($familles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
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
        exit();
    }

    if (isset($_POST['export_csv'])) {
        $this->exportToXLSX();
        exit();
    }

    $jsonProvider = new JsonProvider('../data/models/catalogue.json', "../data/models/avis.json", '../data/models/famille.json');
    $catalogues = $jsonProvider->loadCatalogue();
    try {
        if (isset($_POST['_method']) && $_POST['_method'] == 'PUT') {
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
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
            error_log(print_r($_POST, true));
            $familles = $jsonProvider->loadFamilles();
            if (isset($_POST['id'])) {  
                $id = $_POST['id'];
                
                if ($_POST['form_type'] == 'add_famille') {
                    $newFamille = $_POST['new_famille'];
                    if (!isset($familles[$newFamille])) {
                        $familles[$newFamille] = [];
                    }
                    error_log("Famille ".print_r($familles, true));
                } elseif ($_POST['form_type'] === 'add_sousfamille') {
                    $famille = $_POST['famille'];
                $newSousFamille = $_POST['new_sousfamille'];
                if (isset($familles[$famille]) && !in_array($newSousFamille, $familles[$famille])) {
                    $familles[$famille][] = $newSousFamille;
                }
                } elseif ($_POST['form_type'] === 'add_description') {
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
                }elseif ($_POST["form_type"] === "add_article") {
                    $newArticle = new Catalogue(
                        count($catalogues) + 1,
                        "Nouvel article",
                        [],
                        [],
                        "",
                        [],
                        "",
                        "",
                    );
                    $catalogues[] = $newArticle;
                    $jsonProvider->saveCatalogue($catalogues);

                }

                
            }
            $jsonProvider->saveFamilles($familles);
        } elseif (isset($_POST['_method']) && $_POST['_method'] == 'DELETE') {
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
                error_log("Suppression du produit ID: " . $id);
                error_log(print_r($_POST, true));
                if (isset($_POST['form_type']) && $_POST['form_type'] === 'delete_description') {
                    $descriptionIndex = $_POST['description_index'];
                    foreach ($catalogues as $catalogue) {
                        if ($catalogue->getId() == $id) {
                            $descriptions = $catalogue->getDescription1();
                            if (isset($descriptions[$descriptionIndex])) {
                                unset($descriptions[$descriptionIndex]);
                                $catalogue->setDescription1(array_values($descriptions));
                            }
                            break;
                        }
                    }
                } elseif (isset($_POST['form_type']) && $_POST['form_type'] === 'delete_prix') {
                    $prixIndex = $_POST['prix_index'];
                    error_log("Suppression du prix à l'index: " . $prixIndex);
                    foreach ($catalogues as $catalogue) {
                        if ($catalogue->getId() == $id) {
                            $prixs = $catalogue->getPrix();
                            error_log(print_r($prixs, true));
                            if (isset($prixs[$prixIndex])) {
                                unset($prixs[$prixIndex]);
                                $catalogue->setPrix(array_values($prixs));
                            }
                            break;
                        }
                    }
                } else {
                    foreach ($catalogues as $index => $catalogue) {
                        if ($catalogue->getId() == $id) {
                            unset($catalogues[$index]);
                            $this->removeProductIdFromAvis((int)$id); 
                            break;
                        }
                    }
                }
            }
        }

    
$catalogues = array_values($catalogues);

$jsonProvider->saveCatalogue($catalogues);
$message = 'Les modifications ont été enregistrées avec succès.';
error_log($message);
        if (isset($_POST['upload_image'])) {
            $this->handleImageUpload();
            $index = $_POST['id'];
            $this->redirectTo('gestionarticles?index=' . $index);
        }

        if (isset($_POST['form_type']) && $_POST['form_type'] === 'edit_photos') {
            $this->handlePhotoEdit();
            $index = $_POST['id'];
            $this->redirectTo('gestionarticles?index=' . $index);
        }

        if (isset($_POST['id']) && $_POST['form_type'] !== 'edit_article') {
            $index = $_POST['id'];               
            $this->redirectTo('gestionarticles?index=' . $index);
        } 
            
        $this->redirectTo('gestionarticles');    
        
    } catch (Exception $e) {
        echo "Erreur: " . htmlspecialchars($e->getMessage());
        error_log("Erreur lors de la mise à jour du catalogue: " . $e->getMessage());
    }
}


    private function removeProductIdFromAvis(int $productId): void
{
    $jsonProvider = new JsonProvider('../data/models/catalogue.json', "../data/models/avis.json", '../data/models/famille.json');
    $avis = $jsonProvider->loadAvis();
    
    foreach ($avis as $index => $avi) {
        if ($avi->getIdProduit() == $productId) {
            error_log("Suppression de l'avis pour le produit ID: " . $productId);
            error_log(print_r($avis, true));
            unset($avis[$index]);
            
        }
    }
    $jsonProvider->saveAvis($avis);

}

    private function handlePhotoEdit(): void
    {
        $fileTmpPath = $_FILES["photo"]["tmp_name"];
        $fileName = $_FILES["photo"]["name"];
        $fileType = $_FILES["photo"]["type"];
        $photoIndex = $_POST["photo_index"];
        $id = $_POST["id"];

        error_log("Image reçue: " . $fileName);
        error_log("Type de fichier: " . $fileType);
        $allowedTypes = ["image/png", "image/jpeg"];

        if (in_array($fileType, $allowedTypes)) {
            $destinationDir = __DIR__ . '/../../public/static/images/images_articles/';
            $destinationPath = $destinationDir . $fileName;

            if (!is_dir($destinationDir)) {
                mkdir($destinationDir, 0777, true);
            }

            if (move_uploaded_file($fileTmpPath, $destinationPath)) {
                error_log("Image déplacée avec succès.");
                $this->updatePhotoInJson($id, $photoIndex, $fileName);
            } else {
                echo "Échec du déplacement de l'image.";
            }
        } else {
            echo "Format de fichier non supporté.";
        }
    }

    private function updatePhotoInJson(string $id, string $photoIndex, string $fileName): void
    {
        $jsonFilePath = __DIR__ . '/../../data/models/catalogue.json';
        $jsonData = file_get_contents($jsonFilePath);
        $data = json_decode($jsonData, true);

        foreach ($data as &$produit) {
            if ($produit['id'] == $id) {
                if (isset($produit['photos'][$photoIndex])) {
                    $produit['photos'][$photoIndex] = 'static/images/images_articles/' . $fileName;
                }
            }
        }

        $newJsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (file_put_contents($jsonFilePath, $newJsonData) === false) {
            error_log("Erreur lors de l'écriture du fichier JSON.");
        } else {
            error_log("Fichier JSON mis à jour avec succès.");
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

        if (in_array($fileType, $allowedTypes)) {
            $outputJsonFileName = __DIR__ . '/../../data/models/catalogue.json';
            $outputCsvFileName = __DIR__ . '/../../data/catalogue.csv';
            error_log("Conversion du fichier en JSON et CSV.");
            $this->convertToJSON($fileTmpPath, $outputJsonFileName);
            $this->convertToCSV($fileTmpPath, $outputCsvFileName);
        } else {
            echo "Format de fichier non supporté.";
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
                "nom" => $row['D'],
                "description1" => isset($row['E']) ? explode(';', $row['E']) : [],
                "prix" => []
            ];

            if (!empty($row['G'])) {
                $prixDescriptions = isset($row['F']) ? explode(';', $row['F']) : [];
                $prixTarifs = explode(';', str_replace(['€', ' '], '', $row['G']));

                foreach ($prixTarifs as $index => $prix) {
                    $produit["prix"][] = [
                        "description" => isset($prixDescriptions[$index]) ? trim($prixDescriptions[$index]) : '',
                        "tarif" => trim($prix)
                    ];
                }
            }

            $produit["txt1"] = $row['H'];
            $produit["photos"] = isset($row['I']) ? explode(';', $row['I']) : [];
            $produit["famille"] = $row['B'];
            $produit["sousfamille"] = $row['C'];

            $data[] = $produit;
        }

        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (file_put_contents($outputFileName, $jsonData) === false) {
            error_log("Erreur lors de l'écriture du fichier JSON.");
        } else {
            error_log("Fichier JSON mis à jour avec succès.");
        }
    }

    private function convertToCSV(string $inputFileName, string $outputFileName): void
    {
        $spreadsheet = IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();
        $sheetData = $sheet->toArray(null, true, true, true);

        $file = fopen($outputFileName, 'w');

        foreach ($sheetData as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    }

    private function exportToXLSX(): void
    {
        $jsonProvider = new JsonProvider('../data/models/catalogue.json', "../data/models/avis.json", '../data/models/famille.json');
        $catalogues = $jsonProvider->loadCatalogue();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Famille')
            ->setCellValue('C1', 'Sous-famille')
            ->setCellValue('D1', 'Nom')
            ->setCellValue('E1', 'Descriptions')
            ->setCellValue('F1', 'Prix Descriptions')
            ->setCellValue('G1', 'Prix Tarifs')
            ->setCellValue('H1', 'Texte')
            ->setCellValue('I1', 'Photos');

        $rowNumber = 2;
        foreach ($catalogues as $catalogue) {
            $descriptions = implode(';', $catalogue->getDescription1());
            $prixDescriptions = implode(';', array_column($catalogue->getPrix(), 'description'));
            $prixTarifs = implode(';', array_column($catalogue->getPrix(), 'tarif'));
            $photos = implode(';', $catalogue->getPhotos());

            $sheet->setCellValue('A' . $rowNumber, $catalogue->getId())
                ->setCellValue('B' . $rowNumber, $catalogue->getFamille())
                ->setCellValue('C' . $rowNumber, $catalogue->getSousFamille())
                ->setCellValue('D' . $rowNumber, $catalogue->getNom())
                ->setCellValue('E' . $rowNumber, $descriptions)
                ->setCellValue('F' . $rowNumber, $prixDescriptions)
                ->setCellValue('G' . $rowNumber, $prixTarifs)
                ->setCellValue('H' . $rowNumber, $catalogue->getTxt1())
                ->setCellValue('I' . $rowNumber, $photos);

            $rowNumber++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $xlsxData = ob_get_contents();
        ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="catalogue.xlsx"');
        header('Content-Length: ' . strlen($xlsxData));
        echo $xlsxData;
    }

    private function handleImageUpload(): void
    {
        $fileTmpPath = $_FILES["image"]["tmp_name"];
        $fileName = $_FILES["image"]["name"];
        $fileType = $_FILES["image"]["type"];
        $productId = $_POST["id"];

        error_log("Image reçue: " . $fileName);
        error_log("Type de fichier: " . $fileType);
        $allowedTypes = ["image/png", "image/jpeg"];

        if (in_array($fileType, $allowedTypes)) {
            $destinationPath = __DIR__ . '/../../public/static/images/images_articles/' . $fileName;

            if (move_uploaded_file($fileTmpPath, $destinationPath)) {
                error_log("Image déplacée avec succès.");
                $this->addImagePathToJson($fileName, (int)$productId);
            } else {
                echo "Échec du déplacement de l'image.";
            }
        } else {
            echo "Format de fichier non supporté.";
        }
    }

    private function addImagePathToJson(string $fileName, int $productId): void
    {
        $jsonFilePath = __DIR__ . '/../../data/models/catalogue.json';
        $jsonData = file_get_contents($jsonFilePath);
        $data = json_decode($jsonData, true);

        foreach ($data as &$produit) {
            if ($produit['id'] == $productId) {

                if (!isset($produit['photos'])) {
                    $produit['photos'] = [];
                }
                $produit['photos'][] = '/static/images/images_articles/' . $fileName;
                break;
            }
        }

        $newJsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (file_put_contents($jsonFilePath, $newJsonData) === false) {
            error_log("Erreur lors de l'écriture du fichier JSON.");
        } else {
            error_log("Fichier JSON mis à jour avec succès.");
        }
    }
}
