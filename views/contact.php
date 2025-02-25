<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['envoyer'])) { 
    if (!empty($_POST['mail']) && !empty($_POST['sujet']) && !empty($_POST['message'])) { 
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // à modifier si autre système utilisé que GMAIL
            $mail->SMTPAuth = true;
            $mail->Username = "mail_reception@example.com"; // à compléter par l'adresse mail qui doit recevoir les mails
            $mail->Password = "mdp-application"; // Mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Expéditeur
            $mail->setFrom($_POST['mail'], $_POST['utilisateur']);
            // Destinataire
            $mail->addAddress('mail_reception@example.com');  // à compléter par l'adresse mail qui doit recevoir les mails

            $mail->isHTML(true);
            $mail->Subject = "Mail de contact : " . $_POST['sujet'];
            $mail->Body = "<strong>Mail de :</strong> " . $_POST['utilisateur'] . "<br><strong>Adresse de contact :</strong> " . $_POST['mail'] . "<br><strong>Contenu du message : </strong><br>" . nl2br($_POST['message']);
            $mail->AltBody = "";

            // Envoi du mail
            $mail->send();
            echo "<p style='color:green; text-align:center'>Mail de contact envoyé avec succès</p>";

        } catch (Exception $e) {
            echo "<p style='color:red; text-align:center'>Erreur lors de l'envoi : {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p style='color:blue; text-align:center'>Tous les champs sont requis.</p>";
    }
}

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alata&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="contact.css">
    <title>Contact</title>
</head>
<header>
    <nav id="topnav">
        <div class="search-container">
            <span class="material-symbols-outlined">search</span>
            <input type="search" name="Rechercher" id="rechercher" placeholder="Rechercher">
        </div>
        <ul>
            <li><a href="#">Articles</a></li>
            <li><a href="#">À propos</a></li>
            <li><a href="contact.php" class="nav-link-active">Contact</a></li>
        </ul>
    </nav>

    <nav id="mainnav">
        <div class="logo-container">
            <a id="logo" href="#"><img src="images/logo.png" alt="SFabc" width="120" heigt="auto"></a>
        </div>
    </nav>
</header>
<section class="bandeau">
    <h1>Contactez-moi !</h1>
    <img src="images/fond_bandeau.png" alt="bandeau">
</section>
<body>
    <p id="info-form">Les champs marqués d’un * sont obligatoires.</p>
    <div class="container">
        <form action="contact.php" method="post" class="contact-form">

            <input type="text" id="name" name="utilisateur" placeholder="Vos nom et prénom*" required>
            
            <input type="email" id="email" name="mail" placeholder="Votre email*" required>
            
            <input type="text" id="subject" name="sujet" placeholder="Sujet*" required>
            
            <textarea id="message" name="message" cols="40" rows="5" placeholder="Votre message ..." required></textarea>
            
            <button type="submit" name="envoyer">Envoyer</button>
        </form>
    </div>
</body>
<footer>
    <div class="footer-content">
        <p>SFabc</p>
        <p>123 rue lorem ipsum, 45000 Orléans</p>
        <p>06.02.03.04.05</p>
        <div class="social-icons">
            <a href="#"><img src="images/insta.png" alt="Instagram"></a>
            <a href="#"><img src="images/fb.png" alt="Facebook"></a>
        </div>
    </div>
    <p class="footer-note">IUT d'Orléans - Claire Deneau, Jean-Marc Jorite, Thomas Brossier</p>
</footer>
</html>
