<?php

namespace App\Controller;
use App\Entity\User;
use App\Model\SearchData;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\JobRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

Use App\Repository\UserRepository;



class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(UserRepository $userRepository, jobRepository $jobRepository, Request $request, PaginatorInterface $paginatorInterface): Response
    {  
       $userCount = $userRepository->countAllUsers();
       $SearchData = New SearchData();
       //$currentPage = $request->query->getInt('page', 1);
    
        //$previousPage = max(1, $currentPage - 1);
      
       $form = $this->createForm(SearchType::class, $SearchData);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $SearchData->page = $request->query->getInt('page', 1);
           $job = $jobRepository->findBySearch($SearchData);
        
           return $this->render('home_page/index.html.twig', [
            'form'=>$form->createview(),
            'job'=> $job, 
            //'previous' => $previousPage,
             
        ]);

         }
        return $this->render('home_page/index.html.twig', [
            'form'=>$form->createview(),
            'job'=> $jobRepository->findPublished($request->query->getInt('page', 1)), 
            'userCount' => $userCount,
        ]);

    }

    #[Route('/job/details/{id<[0-9]+>}', name: 'app_job_detail')]
    public function jobDetail($id, jobRepository $jobRepository): Response
    {
        
        $jobId = $jobRepository->find($id);
        $employeur = $jobId->getEmployeur();
        return $this->render('home_page/job_details.html.twig', [
            'jobDetail'=>$jobRepository->find($jobId),
            'employeur'=> $employeur
            
            
        ]);

    }

    #[Route('/job/listes', name: 'listes_jobs')]
    public function liste(jobRepository $jobRepository): Response
    {
    
        return $this->render('RechercherJob/jobListes.html.twig', [
            'job'=>$jobRepository->findAll()
        ]);
    }

    #[Route('afficher/candidatures', name: 'afficher_candidatures')]
    public function candidature(): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer toutes les candidatures de l'utilisateur
        $candidatures = $user->getCandidatures();

        return $this->render('home_page/afficherCandidatures.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }

     #[Route('/test-email', name: 'test_email')]
    public function sendTestEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('jobFinder@gmail.com')
            ->to('dominique.garnier@hotmail.fr')  // Remplacez par votre e-mail
            ->subject('Test Email')
            ->text('This is a test email.')
            ->html('<p>This is a test email.</p>');
            try {
                $mailer->send($email);
                return new Response('Email sent');
            } catch (TransportExceptionInterface $e) {
                // Log the error or handle it as needed
                // For example, you could log the error message
                error_log('Email sending failed: ' . $e->getMessage());
            
                // Return a response indicating the failure
                return new Response('Failed to send email: ' . $e->getMessage());
            }
    }
}
