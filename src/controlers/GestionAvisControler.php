<?php

declare(strict_types=1);

namespace SFabc\controlers;

use \Exception;
use SFabc\dataprovider\Catalogue;
use SFabc\dataprovider\Avis;
use SFabc\dataprovider\JsonProvider;

class GestionAvisControler extends Controler
{
    public function get(string $params): void
    {

        try {

            $jsonProvider = new JsonProvider('../data/models/catalogue.json', "../data/models/avis.json", '../data/models/famille.json');
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
            echo "Erreur: " . htmlspecialchars($e->getMessage());
            error_log("Erreur lors du chargement du catalogue: " . $e->getMessage());
        }
    }


    private function getArticleNamesById(array $avis, array $catalogues): array
    {
        $articleNames = [];

        foreach ($avis as $avi) {
            error_log("Avis: " . $avi->getIdProduit());
            $articleNames[$avi->getId()] = "Article inconnu";
            error_log("Articles name".print_r($articleNames, true));

            foreach ($catalogues as $catalogue) {
                error_log("Catalogue: " . $catalogue->getId());
                if ($catalogue->getId() == $avi->getIdProduit()) {
                    $articleNames[$avi->getId()] = $catalogue->getNom();
                    error_log("Articles finie ".print_r($articleNames, true));
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
        }

        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
            $jsonProvider = new JsonProvider('../data/models/catalogue.json', "../data/models/avis.json", '../data/models/famille.json');
            $avis = $jsonProvider->loadAvis();
            $newAvis = [];
            foreach ($avis as $avi) {
                if ($avi->getId() != $id) {
                    $newAvis[] = $avi;
                }
            }
            $jsonProvider->saveAvis($newAvis);
        }

        header('Location: /gestionavis');
    }
}