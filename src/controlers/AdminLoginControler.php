<?php
declare(strict_types=1);

namespace SFabc\controlers;

class AdminLoginControler extends Controler
{
    public function get(string $params): void
    {
        $this->render('login', []);
    }
    public function post(string $param): void
    {
    // informations à changer
    $admin = "admin";
    $mdp_admin = "password123";


        $nom_utilisateur = $_POST["identifiant"] ?? "";
        $mot_de_passe = $_POST["password"] ?? "";

        if($nom_utilisateur === $admin && $mot_de_passe === $mdp_admin) {
            $_SESSION["user"] = $nom_utilisateur;
            $this->redirectTo('bienvenue');
        } else {
            $erreur = "Identifiants ou mot de passe incorrects";
            echo $erreur;
        }
        $this->render('login', []);
    }
}
