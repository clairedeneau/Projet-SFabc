<?php

declare(strict_types=1);

namespace SFabc\controlers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ControleurContact extends Controler
{
    public function get(string $params): void
    {
        $this->render('contact', []);
    }
    public function post(string $param): void
    {
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
        $this->render('contact', []);
    }
}
