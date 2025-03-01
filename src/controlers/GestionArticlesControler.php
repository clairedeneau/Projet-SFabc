<?php

declare(strict_types=1);

namespace SFabc\controlers;

use \Exception;
use SFabc\dataprovider\Catalogue;
use SFabc\dataprovider\JsonProvider;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GestionArticlesControler extends Controler
{
    private const CATALOGUE_JSON_PATH = '../data/models/catalogue.json';
    private const AVIS_JSON_PATH = '../data/models/avis.json';
    private const FAMILLE_JSON_PATH = '../data/models/famille.json';
    private const IMAGE_DIR = __DIR__ . '/../../public/static/images/images_articles/';

    public function get(string $params): void
    {
        try {
            $jsonProvider = new JsonProvider(self::CATALOGUE_JSON_PATH, self::AVIS_JSON_PATH, self::FAMILLE_JSON_PATH);
            $catalogues = $jsonProvider->loadCatalogue();
            $familles = $jsonProvider->loadFamilles();

            $_SESSION['catalogue'] = $catalogues;

            $selectedCatalogue = null;
            if (isset($_GET['index'])) {
                foreach ($catalogues as $catalogue) {
                    if ($catalogue->getId() == $_GET['index']) {
                        $selectedCatalogue = $catalogue;
                        break;
                    }
                }
            }

            $this->render('gestionarticles', [
                'catalogues' => $catalogues,
                'selectedCatalogue' => $selectedCatalogue,
                'familles' => $familles
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

        $jsonProvider = new JsonProvider(self::CATALOGUE_JSON_PATH, self::AVIS_JSON_PATH, self::FAMILLE_JSON_PATH);
        $catalogues = $jsonProvider->loadCatalogue();
        $familles = $jsonProvider->loadFamilles();

        try {
            if (isset($_POST['_method'])) {
                switch ($_POST['_method']) {
                    case 'PUT':
                        $this->handlePutRequest($catalogues);
                        break;
                    case 'POST':
                        $this->handlePostRequest($catalogues, $familles);
                        break;
                    case 'DELETE':
                        $this->handleDeleteRequest($catalogues, $familles);
                        break;
                }
            }

            $jsonProvider->saveCatalogue($catalogues);
            $jsonProvider->saveFamilles($familles);

            if (isset($_POST['upload_image'])) {
                $this->handleImageUpload();
                $this->redirectTo('gestionarticles?index=' . $_POST['id']);
            }

            if (isset($_POST['form_type']) && $_POST['form_type'] === 'edit_photos') {
                $this->handlePhotoEdit();
                $this->redirectTo('gestionarticles?index=' . $_POST['id']);
            }

            if (isset($_POST['id']) && $_POST['form_type'] !== 'edit_article') {
                $this->redirectTo('gestionarticles?index=' . $_POST['id']);
            }

            $this->redirectTo('gestionarticles');
        } catch (Exception $e) {
            echo "Erreur: " . htmlspecialchars($e->getMessage());
            error_log("Erreur lors de la mise à jour du catalogue: " . $e->getMessage());
        }
    }

    private function handlePutRequest(array &$catalogues): void
    {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
            foreach ($catalogues as $catalogue) {
                if ($catalogue->getId() == $id) {
                    switch ($_POST['form_type']) {
                        case 'edit_article':
                            $catalogue->setNom($_POST['nom']);
                            $catalogue->setFamille($_POST['famille']);
                            $catalogue->setSousFamille($_POST['sousFamille']);
                            $catalogue->setTxt1($_POST['text1']);
                            break;
                        case 'edit_description':
                            $this->updateDescriptions($catalogue);
                            break;
                        case 'edit_prix':
                            $this->updatePrix($catalogue);
                            break;
                    }
                    break;
                }
            }
        }
    }

    private function handlePostRequest(array &$catalogues, array &$familles): void
    {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
            error_log("ID: " . $id);
            switch ($_POST['form_type']) {
                case 'add_famille':
                    $this->addFamille($familles);
                    break;
                case 'add_sousfamille':
                    $this->addSousFamille($familles);
                    break;
                case 'add_description':
                    $this->addDescription($catalogues, $id);
                    break;
                case 'add_prix':
                    $this->addPrix($catalogues, $id);
                    break;
                case 'add_article':
                    $this->addArticle($catalogues);
                    break;
            }
        }
    }

    private function handleDeleteRequest(array &$catalogues, array &$familles): void
    {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
            switch ($_POST['form_type']) {
                case 'delete_description':
                    $this->deleteDescription($catalogues, $id);
                    break;
                case 'delete_prix':
                    $this->deletePrix($catalogues, $id);
                    break;
                case 'delete_sousfamille':
                    $this->deleteSousFamille($catalogues, $familles);
                    break;
                case 'delete_famille':
                    $this->deleteFamille($catalogues, $familles);
                    break;
                default:
                    $this->deleteArticle($catalogues, $id);
                    break;
            }
        }
    }

    private function updateDescriptions(Catalogue $catalogue): void
    {
        $currentDescriptions = [];
        foreach ($catalogue->getDescription1() as $index => $description) {
            $currentDescriptions[] = $_POST['modif_description' . $index];
        }
        $catalogue->setDescription1($currentDescriptions);
    }

    private function updatePrix(Catalogue $catalogue): void
    {
        $currentPrix = [];
        foreach ($catalogue->getPrix() as $index => $tarif) {
            $currentPrix[] = ['description' => $_POST['modif_description' . $index], 'tarif' => $_POST['modif_prix' . $index]];
        }
        $catalogue->setPrix($currentPrix);
    }

    private function addFamille(array &$familles): void
    {
        $newFamille = $_POST['new_famille'];
        if (!isset($familles[$newFamille])) {
            $familles[$newFamille] = [];
        }
    }

    private function addSousFamille(array &$familles): void
    {
        $famille = $_POST['famille'];
        $newSousFamille = $_POST['new_sousfamille'];
        if (isset($familles[$famille]) && !in_array($newSousFamille, $familles[$famille])) {
            $familles[$famille][] = $newSousFamille;
        }
    }

    private function addDescription(array &$catalogues, int $id): void
    {
        $newDescription = $_POST['new_description'];
        foreach ($catalogues as $catalogue) {
            if ($catalogue->getId() == $id) {
                $currentDescriptions = $catalogue->getDescription1();
                $currentDescriptions[] = $newDescription;
                $catalogue->setDescription1($currentDescriptions);
                break;
            }
        }
    }

    private function addPrix(array &$catalogues, int $id): void
    {
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

    private function addArticle(array &$catalogues): void
    {
        $newArticle = new Catalogue(
            count($catalogues) + 1,
            "Nouvel article",
            [],
            [],
            "",
            [],
            "",
            ""
        );
        $catalogues[] = $newArticle;
    }

    private function deleteDescription(array &$catalogues, int $id): void
    {
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
    }

    private function deletePrix(array &$catalogues, int $id): void
    {
        $prixIndex = $_POST['prix_index'];
        foreach ($catalogues as $catalogue) {
            if ($catalogue->getId() == $id) {
                $prixs = $catalogue->getPrix();
                if (isset($prixs[$prixIndex])) {
                    unset($prixs[$prixIndex]);
                    $catalogue->setPrix(array_values($prixs));
                }
                break;
            }
        }
    }

    private function deleteSousFamille(array &$catalogues, array &$familles): void
    {
        $sousfamille = $_POST['sousfamille'];
        foreach ($catalogues as $catalogue) {
            if ($catalogue->getSousFamille() == $sousfamille) {
                $catalogue->setSousFamille('');
            }
        }
        foreach ($familles as $famille => $sousFamilles) {
            if (($key = array_search($sousfamille, $sousFamilles)) !== false) {
                unset($sousFamilles[$key]);
                $familles[$famille] = array_values($sousFamilles);
            }
        }
    }

    private function deleteFamille(array &$catalogues, array &$familles): void
    {
        $familleToDelete = $_POST['famille'];
        foreach ($catalogues as $catalogue) {
            if ($catalogue->getFamille() == $familleToDelete) {
                $catalogue->setFamille('');
            }
            if (isset($familles[$familleToDelete]) && in_array($catalogue->getSousFamille(), $familles[$familleToDelete])) {
                $catalogue->setSousFamille('');
            }
        }

        if (isset($familles[$familleToDelete])) {
            unset($familles[$familleToDelete]);
        }
        foreach ($familles as $famille => $sousFamilles) {
            foreach ($sousFamilles as $key => $sousFamille) {
                if ($sousFamille == $familleToDelete) {
                    unset($sousFamilles[$key]);
                }
            }
            $familles[$famille] = array_values($sousFamilles);
        }
    }

    private function deleteArticle(array &$catalogues, int $id): void
    {
        foreach ($catalogues as $index => $catalogue) {
            if ($catalogue->getId() == $id) {
                unset($catalogues[$index]);
                $this->removeProductIdFromAvis($id);
                break;
            }
        }
    }

    private function removeProductIdFromAvis(int $productId): void
    {
        $jsonProvider = new JsonProvider(self::CATALOGUE_JSON_PATH, self::AVIS_JSON_PATH, self::FAMILLE_JSON_PATH);
        $avis = $jsonProvider->loadAvis();

        foreach ($avis as $index => $avi) {
            if ($avi->getIdProduit() == $productId) {
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

        $allowedTypes = ["image/png", "image/jpeg"];

        if (in_array($fileType, $allowedTypes)) {
            $destinationPath = self::IMAGE_DIR . $fileName;

            if (!is_dir(self::IMAGE_DIR)) {
                mkdir(self::IMAGE_DIR, 0777, true);
            }

            if (move_uploaded_file($fileTmpPath, $destinationPath)) {
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
        $jsonFilePath = self::CATALOGUE_JSON_PATH;
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
        file_put_contents($jsonFilePath, $newJsonData);
    }

    private function handleFileUpload(): void
    {
        $fileTmpPath = $_FILES["file"]["tmp_name"];
        $fileName = $_FILES["file"]["name"];
        $fileType = $_FILES["file"]["type"];

        $allowedTypes = ["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"];

        if (in_array($fileType, $allowedTypes)) {
            $outputJsonFileName = self::CATALOGUE_JSON_PATH;
            $outputCsvFileName = __DIR__ . '/../../data/catalogue.csv';
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
        file_put_contents($outputFileName, $jsonData);
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
        $jsonProvider = new JsonProvider(self::CATALOGUE_JSON_PATH, self::AVIS_JSON_PATH, self::FAMILLE_JSON_PATH);
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
            $destinationPath =  self::IMAGE_DIR . $fileName;

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
        $jsonFilePath = self::CATALOGUE_JSON_PATH;
        $jsonData = file_get_contents($jsonFilePath);
        $data = json_decode($jsonData, true);

        foreach ($data as &$produit) {
            if ($produit['id'] == $productId) {

                if (!isset($produit['photos'])) {
                    $produit['photos'] = [];
                }
                $produit['photos'][] = 'static/images/images_articles/' . $fileName;
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
