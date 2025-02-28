<?php

declare(strict_types=1);

namespace SFabc\controlers;

use SFabc\dataprovider\JsonProvider;

class ControleurRechercheArticles extends Controler
{
    public function get(string $params): void
    {
        $jp = new JsonProvider("../data/models/catalogue.json","");
        $articles = $jp->loadCatalogue();
    
        $query = isset($_GET['recherche']) ? strtolower(trim($_GET['recherche'])) : "";
        $articles_filtre = [];
        if($query) {
            foreach ($articles as $article) {
                if ($article instanceof \SFabc\dataprovider\Catalogue && $article->getNom() !== null) {
                    if (strpos(strtolower($article->getNom()), $query) !== false) {
                        $articles_filtre[] = $article;
                    }
                }
                
            }
        } else {
            $articles_filtre = $articles;
        }
        $this->render('recherche-articles', ['articles' => $articles_filtre, 'query' => $query]);
    }
}