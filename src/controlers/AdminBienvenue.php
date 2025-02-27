<?php
declare(strict_types=1);

namespace SFabc\controlers;

class AdminBienvenue extends Controler
{

   private string $filePath = __DIR__ . '/../../data/models/information.json';

    public function get(string $params): void
    {

        $json = file_get_contents($this->filePath);
        $data = json_decode($json, true);
        $this->render('bienvenue', ['data' => $data]);
    } 

    public function post(string $params): void
    {
        $email = $_POST['email'] ?? '';
        $tel = $_POST['tel'] ?? '';
        $facebook = $_POST['facebook'] ?? '';
        $instagram = $_POST['instagram'] ?? '';
        $adresse = $_POST['adresse'] ?? '';

        $nouvelle_donnees = [
            'email' => $email,
            'tel' => $tel,
            'facebook' => $facebook,
            'instagram' => $instagram,
            'adresse' => $adresse
        ];

        file_put_contents($this->filePath, json_encode($nouvelle_donnees, JSON_PRETTY_PRINT));

        $this->render('bienvenue', ['data' => $data, 'succes' => 'Informations mises à jour avec succès !']);
    }
}
