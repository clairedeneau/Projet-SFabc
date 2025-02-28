<?php

declare(strict_types=1);

namespace SFabc\controlers;

class AvisControler extends Controler
{
    private string $filePath = __DIR__ . '/../../data/models/avis.json';

    public function get(string $params): void
    {
        $data = file_get_contents($this->filePath);
        $json = json_decode($data, true);
        $this->render('avis', ['data' => $json['avis']]);
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
                'idProduit' => 1,
                'user' => $nom,
                'note' => $note,
                'comment' => $commentaire,
                'date' => $date
            ];

            $json['avis'][] = $newAvis;

            file_put_contents($this->filePath, json_encode($json, JSON_PRETTY_PRINT));
            header("Location: /avis");
            exit;
        }
        $this->render('avis', ['succes' => 'Avis ajouté avec succès !']);
    }
}