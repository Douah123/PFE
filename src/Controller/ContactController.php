<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContactController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the contact entity to the database
            $entityManager->persist($contact);
            $entityManager->flush();

            // Send email using PHPMailer
            $this->sendContactEmail($contact);
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function sendContactEmail(Contact $contact)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 587;
            $mail->Username = 'ff2b5105884cb2';
            $mail->Password = '83a8d8ab55fad5';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            $mail->Encoding = 'base64';

            // Recipients
            $mail->setFrom($contact->getEmail(), ($contact->getFirsName()));
            $mail->addAddress('jobFinder@gmail.com', 'Job Finder'); // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = ($contact->getSubject());
            $mail->Body    = ($contact->getMessage());

            $mail->send();
        } catch (Exception $e) {
            // Handle the exception (e.g., log the error)
            $this->addFlash('error', 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        }
    }
}
