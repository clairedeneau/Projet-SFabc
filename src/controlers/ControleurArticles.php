<?php

declare(strict_types=1);

namespace SFabc\controlers;

use SFabc\dataprovider\JsonProvider;

class ControleurArticles extends Controler
{
    public function get(string $params): void
    {
        $jp = new JsonProvider("../data/models/catalogue.json");
        $articles = $jp->loadCatalogue();
        $this->render('articles', ['articles' => $articles]);
    }
}