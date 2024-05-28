<?php

// src/Service/PHPMailerService.php
namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\Mime\Address;

class PHPMailerService
{
    private $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function sendTemplatedEmail($recipientEmail, $recipientName, $subject, $templateName, $context)
    {
        try {
            // Créez une instance de PHPMailer
            $mail = new PHPMailer(true);

            // Configurez PHPMailer
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 587;
            $mail->Username = 'ff2b5105884cb2';
            $mail->Password = '83a8d8ab55fad5';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // Générez le contenu HTML à partir du template Twig
            $htmlContent = $this->twig->render($templateName, $context);

            // Configurez l'email
            $mail->setFrom('jobFinder@gmail.com', 'Douah');
            $mail->addAddress($recipientEmail, $recipientName);
            $mail->Subject = $subject;
            $mail->Body = $htmlContent;
            $mail->isHTML(true);

            // Envoyez l'email avec PHPMailer
            $mail->send();
            return true; // Succès
        } catch (Exception $e) {
            // En cas d'erreur
            return $e->getMessage();
        }
    }
}
