<?php

declare(strict_types=1);

namespace SFabc\controlers;

use \Exception;
use SFabc\dataprovider\JsonProvider;

class GestionAvisControler extends Controler
{
    private const CATALOGUE_JSON_PATH = '../data/models/catalogue.json';
    private const AVIS_JSON_PATH = '../data/models/avis.json';
    private const FAMILLE_JSON_PATH = '../data/models/famille.json';

    public function get(string $params): void
    {
        try {

            $jsonProvider = new JsonProvider(self::CATALOGUE_JSON_PATH, self::AVIS_JSON_PATH, self::FAMILLE_JSON_PATH);

            $avis = $jsonProvider->loadAvis();

            $catalogues = $jsonProvider->loadCatalogue();
            $_SESSION['avis'] = [];
            $_SESSION['catalogue'] = [];

            foreach ($avis as $avi) {
                $_SESSION['avis'][] = $avi;
            }

            foreach ($catalogues as $catalogue) {
                $_SESSION['catalogue'][] = $catalogue;
            }

            $articleNames = $this->getArticleNamesById($_SESSION['avis'], $_SESSION['catalogue']);

            $this->render('gestionavis', [
                'avis' => $avis,
                'catalogues' => $catalogues,
                'articleNames' => $articleNames
            ]);
        } catch (Exception $e) {
            $this->handleException($e, "Erreur lors du chargement du catalogue");
        }
    }

    private function getArticleNamesById(array $avis, array $catalogues): array
    {
        $articleNames = [];

        foreach ($avis as $avi) {
            $articleNames[$avi->getId()] = "Article inconnu";

            foreach ($catalogues as $catalogue) {
                if ($catalogue->getId() == $avi->getIdProduit()) {
                    $articleNames[$avi->getId()] = $catalogue->getNom();
                    break;
                }
            }
        }

        return $articleNames;
    }

    public function post(string $params): void
    {
        if (isset($_POST['deconnexion'])) {
            session_destroy();
            header('Location: connexionAdmin.php');
            exit();
        }

        if (isset($_POST['id'])) {
            $this->deleteAvis((int)$_POST['id']);
        }

        header('Location: /gestionavis');
    }


    private function deleteAvis(int $id): void
    {
        try {
            $jsonProvider = new JsonProvider(self::CATALOGUE_JSON_PATH, self::AVIS_JSON_PATH, self::FAMILLE_JSON_PATH);
            $avis = $jsonProvider->loadAvis();
            $newAvis = [];
            foreach ($avis as $avi) {
                if ($avi->getId() != $id) {
                    $newAvis[] = $avi;
                }
            }
            $jsonProvider->saveAvis($newAvis);
        } catch (Exception $e) {
            $this->handleException($e, "Erreur lors de la suppression de l'avis");
        }
    }

    private function handleException(Exception $e, string $message): void
    {
        echo "Erreur: " . htmlspecialchars($e->getMessage());
        error_log($message . ": " . $e->getMessage());
    }
}
