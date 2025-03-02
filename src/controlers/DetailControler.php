<?php

declare(strict_types=1);

namespace SFabc\controlers;

use SFabc\dataprovider\Catalogue;
use SFabc\dataprovider\JsonProvider;

class DetailControler extends Controler
{
    public function get(string $params): void
    {
        $jp = new JsonProvider();
        $articles = $jp->loadCatalogue();
        $avis = $jp->loadAvis(intval($params));
        $article = Catalogue::getProduitById($articles, $params);
        $article->setAvis($avis);
        $this->render('detail', ['article' => $article]);
    }
}