<?php

declare(strict_types=1);

namespace SFabc\controlers;

use SFabc\dataprovider\JsonProvider;

class ControleurArticles extends Controler
{
    public function get(string $params): void
    {
        $jp = new JsonProvider();
        $articles = $jp->loadCatalogue();
        $this->render('articles', ['articles' => $articles]);
    }
}