<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Employeur;
use App\Form\EmployeurType;

class EmployeurController extends AbstractController
{
    #[Route('/employeur', name: 'app_employeur')]
    public function formInsc(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
           
            return $this->redirectToRoute('app_login');
        }

        $employeur = new Employeur();
        $form = $this->createForm(EmployeurType::class, $employeur);
        $form->handleRequest($request);
       

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur actuel
            $user = $this->getUser();
            
            // Associer l'utilisateur à l'employeur
            $employeur->setUser($user);

            // Persister et enregistrer l'employeur
            
            $entityManager->persist($employeur);
            $entityManager->flush();

            // Rediriger vers la page d'accueil des employeurs
            return $this->redirectToRoute('accueil_employeur');
        }


        return $this->render('employeur/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/employeur/accueil', name: 'accueil_employeur')]
    public function accueilEmployeur(): Response
    {
    
        return $this->render('employeur/index.html.twig');
    }
}
