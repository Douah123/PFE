<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\Candidature;
use App\Repository\CandidatureRepository;
use App\Form\JobType;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Employeur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\StatusCandidatureType;

#[Route('/job')]
class JobController extends AbstractController
{
    #[Route('/', name: 'app_job_index', methods: ['GET'])]
    public function index(JobRepository $jobRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
           
            return $this->redirectToRoute('app_login');
        }
        if ($user->getEmployeur()) {
            $employeur = $user->getEmployeur();
            $job = $jobRepository->findBy(['employeur' => $employeur]);
            
       
        return $this->render('job/index.html.twig', [
            'jobs' => $job,
        ]);
        }
    }

    #[Route('/new', name: 'app_job_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
       
        $user = $this->getUser();
        if (!$user || !$user->getEmployeur()) {
            return $this->redirectToRoute('app_employeur');
        }
        $job = new Job();
        $job->setEmployeur($user->getEmployeur());
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $job->setCreatedAt(new \datetime);
            $job->setUpdatedAt(new \datetime);
            $date = new \DateTime();
            $date->add(new \DateInterval('P30D')); 
            $job->setExpiresAt($date);

            $entityManager->persist($job);
            $entityManager->flush();

            return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job/new.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_show', methods: ['GET'])]
    public function show(Request $request, Job $job, EntityManagerInterface $entityManager): Response
    {
        $candidature = $job->getCandidatures();
        return $this->render('job/show.html.twig', [
            'job' => $job,
            'candidature' => $candidature,
            
            
        ]);
    }

    #[Route('/job/{id}/accept/{candidatureId}', name: 'accept_candidature', methods: ['POST'])]
    public function acceptCandidature(Request $request, $id, Job $job, $candidatureId, EntityManagerInterface $entityManager): Response
    {
        

        // Récupérer la candidature à accepter
        $candidature = $entityManager->getRepository(Candidature::class)->find($candidatureId,);
        //dd(candidature); die;
        if (!$candidature) {
            throw $this->createNotFoundException('La candidature n\'existe pas.');
        }
        else{
            $candidature->setStatus('Acceptée');
        
            $entityManager->persist($candidature);
            $entityManager->flush();
            // Créer le formulaire pour le statut de la candidature
            $statusForm = $this->createForm(StatusCandidatureType::class, null, [
                'action' => $this->generateUrl('accept_candidature', ['id' => $job->getId(), 'candidatureId' => $candidature->getId()]),
                'method' => 'POST',
            ]);
        
            

            // Traiter la soumission du formulaire
            $statusForm->handleRequest($request);
            if ($statusForm->isSubmitted() && $statusForm->isValid()) {
                $status = $statusForm->get('status')->getData();
                

             // Obtenez la candidature associée
                $candidature = $statusForm->get('candidature')->getData();

    // Mettez à jour le statut de la candidature
                if ($status === 'acceptee') {
        // Mettre à jour le statut de la candidature à "Acceptée"
               
                $candidature->setStatus('Acceptée');
            }
                $entityManager->persist($candidature);
                $entityManager->flush();

                // Rediriger vers la page d'affichage de l'offre
                return $this->redirectToRoute('app_job_show', ['id' => $id]);
            }
        }
        return $this->render('job/show.html.twig', [
            'job' => $job,
            'candidature' => $candidature,
            'statusForm' => $statusForm->createView(),
        ]);
    }


    #[Route('/job/{id}/refuse/{candidatureId}', name: 'refuse_candidature', methods: ['POST'])]
    public function refuseCandidature(Request $request, $id, Job $job, $candidatureId, EntityManagerInterface $entityManager): Response
    {
        // Récupérer la candidature à refuser
        $candidature = $entityManager->getRepository(Candidature::class)->find($candidatureId);
        if (!$candidature) {
            throw $this->createNotFoundException('La candidature n\'existe pas.');
        }
        else{
            // Créer le formulaire pour le statut de la candidature
            $statusForm = $this->createForm(StatusCandidatureType::class, null, [
                'action' => $this->generateUrl('accept_candidature', ['id' => $job->getId(), 'candidatureId' => $candidature->getId()]),
                'method' => 'POST',
            ]);
            
            // Traiter la soumission du formulaire
            $statusForm->handleRequest($request);
            if ($statusForm->isSubmitted() && $statusForm->isValid()) {
                $status = $statusForm->get('status')->getData();

                // Obtenez la candidature associée
                $candidature = $statusForm->get('candidature')->getData();
                if ($status === 'refusee') {
                    // Mettre à jour le statut de la candidature à "Acceptée"
                    $candidature->setStatus('Refusée');
                }
                
                
                $entityManager->persist($candidature);
                $entityManager->flush();
        
                // Rediriger vers la page d'affichage de l'offre
                return $this->redirectToRoute('app_job_show', ['id' => $id]);
            }
        }
        return $this->render('job/show.html.twig', [
            'job' => $job,
            'candidature' => $candidature,
            'statusForm' => $statusForm->createView(),
        ]);
    }
    

    #[Route('/{id}/edit', name: 'app_job_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Job $job, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $job->setUpdatedAt(new \datetime);
            $entityManager->flush();

            return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job/edit.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_delete', methods: ['POST'])]
    public function delete(Request $request, Job $job, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$job->getId(), $request->request->get('_token'))) {
            $entityManager->remove($job);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
    }
}
