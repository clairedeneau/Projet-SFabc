<?php

declare(strict_types=1);

namespace SFabc\controlers;

class ControleurRechercheArticles extends Controler
{
    public function get(string $params): void
    {
        // réception des articles à changer => avec la BD !
        $articles = [
            [
                "nom" => "Couteau en bois personnalisé",
                "prix" => "À partir de 15.00 €",
                "image" => "couteaux/couteau gravure personnalisée 1.jpg"
            ],
            [
                "nom" => "Eclairage ambiance bouteille - grand modèle",
                "prix" => "17.90 €",
                "image" => "bouteille/bouteille_lampe_led_gravure_personnalisee_recyclage_surcyclage_upcycling_cadeau_1.jpg"
            ],
            [
                "nom" => "Aimants bois gravés personnalisés",
                "prix" => "À partir de 15.00 €",
                "image" => "aimants/aimants gravure et découpe personnalisee bois recyclage surcyclage upcycling 1.png"
            ],
            [
                "nom" => "Jack en bois personnalisé",
                "prix" => "À partir de 26.00 €",
                "image" => "jeux/jeux_societe_jackpot_bois_recyclage_surcyclage_upcycling_palette_5.jpg"
            ]
            ];
    
        $query = isset($_GET['recherche']) ? strtolower(trim($_GET['recherche'])) : "";
        $articles_filtre = [];
        if($query) {
            foreach ($articles as $article) {
                if (strpos(strtolower($article["nom"]), $query) !== false) {
                    $articles_filtre[] = $article;
                }
            }
        } else {
            $articles_filtre = $articles;
        }
        $this->render('recherche-articles', ['articles' => $articles_filtre, 'query' => $query]);
    }
}