<?php

declare(strict_types=1);

namespace SFabc\controlers;

use SFabc\dataprovider\Catalogue;
use SFabc\dataprovider\Avis;
use SFabc\dataprovider\JsonProvider;

class AvisControler extends Controler
{
    private string $filePath = __DIR__ . '/../../data/models/avis.json';

    public function get(string $params): void
    {
        $jp = new JsonProvider('../data/models/catalogue.json', "../data/models/avis.json", '../data/models/famille.json');
        $articles = $jp->loadCatalogue();
        $avis = $jp->loadAvis(intval($params));
        $article = Catalogue::getProduitById($articles, $params);
        $article->setAvis($avis);
        
        $this->render('avis', ['articleAvis' => $article]);
    }

    public function post(string $params): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $nom = htmlspecialchars($_POST['nom']);
            $note = (int)$_POST['note'];
            $commentaire = htmlspecialchars($_POST['comment']);

            $date = date('Y-m-d');

            $data = file_get_contents($this->filePath);
            $json = json_decode($data, true);

            $newAvis = [
                'id' => count($json['avis']) + 1,
                'idProduit' => intval($params),
                'user' => $nom,
                'note' => $note,
                'comment' => $commentaire,
                'date' => $date
            ];

            $json['avis'][] = $newAvis;

            file_put_contents($this->filePath, json_encode($json, JSON_PRETTY_PRINT));
        }

        $jp = new JsonProvider('../data/models/catalogue.json', "../data/models/avis.json", '../data/models/famille.json');
        $articles = $jp->loadCatalogue();
        $avis = $jp->loadAvis(intval($params));
        $article = Catalogue::getProduitById($articles, $params);
        $article->setAvis($avis);

        $this->render('avis', ['articleAvis' => $article, 'succes' => 'Avis ajouté avec succès !']);
    }
}