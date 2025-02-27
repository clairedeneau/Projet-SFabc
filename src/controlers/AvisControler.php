<?php

declare(strict_types=1);

namespace SFabc\controlers;

class AvisControler extends Controler
{
    private string $filePath = __DIR__ . '/../../data/models/avis.json';

    public function get(string $params): void
    {

        $json = file_get_contents($this->filePath);
        $data = json_decode($json, true);
        $this->render('avis', ['data' => $data]);
    }

    public function post(string $params): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nom = htmlspecialchars($_POST['nom']);
            $email = htmlspecialchars($_POST['email']);
            $note = (int)$_POST['note'];
            $commentaire = htmlspecialchars($_POST['comment']);

            $date = date('Y-m-d');

            $data = file_get_contents($this->filePath);
            $json = json_decode($data, true);

            $newAvis = [
                'idProduit' => 1,
                'user' => $nom,
                'mail' => $email,
                'note' => $note,
                'comment' => $commentaire,
                'date' => $date
            ];

            $json['avis'][] = $newAvis;

            file_put_contents($this->filePath, json_encode($json, JSON_PRETTY_PRINT));
        }
        $this->render('avis', ['succes' => 'Avis ajouté avec succès !']);
    }
}