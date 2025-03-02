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
        $jp = new JsonProvider();
        $articles = $jp->loadCatalogue();
        $avis = $jp->loadAvis(intval($params));
        $article = Catalogue::getProduitById($articles, $params);
        $article->setAvis($avis);
        
        $this->render('avis', ['articleAvis' => $article]);
    }

    public function post(string $params): void
    {
        $jp = new JsonProvider();

        $nom = htmlspecialchars($_POST['nom']);
        $note = (int)$_POST['note'];
        $commentaire = htmlspecialchars($_POST['comment']);

        $date = date('Y-m-d');

        $newAvis = new Avis(-1, intval($params), $nom, $note, $commentaire, $date);

        $jp->addAvis($newAvis);

        $articles = $jp->loadCatalogue();
        $avis = $jp->loadAvis(intval($params));
        $article = Catalogue::getProduitById($articles, $params);
        $article->setAvis($avis);

        $this->render('avis', ['articleAvis' => $article, 'succes' => 'Avis ajouté avec succès !']);
    }
}