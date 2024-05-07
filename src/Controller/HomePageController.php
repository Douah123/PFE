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




class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(jobRepository $jobRepository, Request $request, PaginatorInterface $paginatorInterface): Response
    {  
       
       $SearchData = New SearchData();
      
       $form = $this->createForm(SearchType::class, $SearchData);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $SearchData->page = $request->query->getInt('page', 1);
           $job = $jobRepository->findBySearch($SearchData);
        
           return $this->render('home_page/index.html.twig', [
            'form'=>$form->createview(),
            'job'=> $job, 
             
        ]);

         }
        return $this->render('home_page/index.html.twig', [
            'form'=>$form->createview(),
            'job'=> $jobRepository->findPublished($request->query->getInt('page', 1)), 
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
}
