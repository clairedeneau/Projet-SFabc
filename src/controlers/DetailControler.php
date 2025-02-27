<?php

declare(strict_types=1);

namespace SFabc\controlers;

use SFabc\dataprovider\Catalogue;
use SFabc\dataprovider\JsonProvider;

class DetailControler extends Controler
{
    public function get(string $params): void
    {
        $jp = new JsonProvider("../data/models/catalogue.json");
        $articles = $jp->loadCatalogue();
        $article = Catalogue::getProduitById($articles, $params);
        $this->render('detail', ['article' => $article]);
    }
}