<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Job;
use App\Entity\Candidature;
use App\Form\CandidatureType;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
class CandidatureController extends AbstractController
{
    #[Route('/postuler/{id}', name: 'postuler')]
    public function index(Request $request, EntityManagerInterface $entityManager, $id, jobRepository $jobRepository): Response
    {
        $user = $this->getUser();
        $job = $jobRepository->find($id);
        if (!$user) {
           
            return $this->redirectToRoute('app_login');
        }
        if (!$job) {
            throw $this->createNotFoundException('L\'offre d\'emploi n\'existe pas');
        }

        // Vérifier si l'utilisateur a déjà postulé pour cette offre
    $existingCandidature = $entityManager->getRepository(Candidature::class)->findOneBy([
        'user_' => $user,
        'offer' => $job,
    ]);
    if ($existingCandidature) {
        $this->addFlash('warning', 'Vous avez deja postulé a cette offre');
        return $this->redirectToRoute('app_home_page');

    }

        $candidature = new Candidature();
        $candidature->setUser($user);
        $candidature->setOffer($job);
        $candidature->setStatus('En attente'); // Initialisation de l'état à "En attente"


        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($candidature);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez postulé avec succès.');

            return $this->redirectToRoute('postuler', ['id' => $job->getId()]);
        }
        elseif($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('error', 'Une erreur s\'est produite lors de la tentative de postulation.');


        }

        return $this->render('candidature/postuler.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/candidature/confirm', name: 'confirmation_candidature')]
    public function confirmCandidature(): Response
    {
    
        return $this->render('candidature/index.html.twig');
    }
}
