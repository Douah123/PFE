<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Employeur;
use App\Form\EmployeurEditType;
use App\Form\EmployeurType;
use App\Entity\User;
use App\Entity\Job;
use App\Repository\JobRepository;
use App\Form\UserPasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    #[Route('/editProfileEmployer/{id}', name: 'employer_edit', methods: ['GET', 'POST'])]
    public function index(int $id, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
    $user = $this->getUser();
    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    $employeur = $entityManager->getRepository(Employeur::class)->find($id);
   
    if (!$employeur || $employeur->getUser() !== $user) {
        return $this->redirectToRoute('listes_jobs');
    }

    // Récupération du job publié par cet employeur
    $job = $employeur->getJob(); // Supposons que vous ayez une méthode getJob() dans votre entité Employeur qui renvoie l'entité Job associée

    $form = $this->createForm(EmployeurEditType::class, $employeur);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $plainPassword = $form->get('plainPassword')->getData();
        if (!$hasher->isPasswordValid($user, $plainPassword)) {
            $this->addFlash('error', 'Mot de passe incorrect.');
            return $this->redirectToRoute('employer_edit', ['id' => $id]);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Profil employeur mis à jour avec succès.');
        return $this->redirectToRoute('accueil_employeur');
    }

    return $this->render('employeur/editEmployeur.html.twig', [
        'form' => $form->createView(),
        'employeur' => $employeur,
        'user' => $user,
        'job' => $job,
    ]);
}



}
