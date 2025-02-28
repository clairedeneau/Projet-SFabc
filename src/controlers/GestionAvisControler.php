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
            error_log("Chargement des avis réussi");
            $jsonProvider = new JsonProvider('',"../data/models/avis.json", '');
            $avis = $jsonProvider->loadAvis();
            $jsonProvider = new JsonProvider('../data/models/catalogue.json',"../data/models/avis.json",'');
            $catalogues = $jsonProvider->loadCatalogue();

            error_log("Chargement des catalogues réussi");
           

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
        }

        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
            $jsonProvider = new JsonProvider('','../data/models/avis.json', '');
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