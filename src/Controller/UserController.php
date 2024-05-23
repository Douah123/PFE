<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserType;
use App\Entity\User;
Use App\Repository\UserRepository;
use App\Form\UserPasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
class UserController extends AbstractController

{
    #[Route('/editProfile/{id}', name: 'user_edit', methods: ['GET', 'POST'])]
    public function index(User $user, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        
        if (!$this->getUser()) {
           
            return $this->redirectToRoute('app_login');
        }
        if ($this->getUser() !== $user) {
           
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à modifier ce profil.');
    
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $plainPassword = $form->get('plainPassword')->getData();
            if($hasher->isPasswordValid($user, $plainPassword)){
                
                $entityManager->persist($user);
                $entityManager->flush();
                
            }
            
            return $this->redirectToRoute('app_home_page');
            
        }
        return $this->render('user/boot.html.twig', [
            'form' => $form->createview(),
            'user' => $user,
        ]);
    }

    #[Route('/editPassword/{id}', name: 'password_edit', methods: ['GET', 'POST'])]
    public function editPassword(User $user, Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
           
            return $this->redirectToRoute('app_login');
        }
        if ($this->getUser() !== $user) {
           
            return $this->redirectToRoute('listes_jobs');
        }
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $plainPassword = $form->get('plainPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();
            $hashedPassword = $hasher->hashPassword($user, $newPassword);
            if($hasher->isPasswordValid($user, $plainPassword)){
                $user->setPassword($hashedPassword);
                $entityManager->persist($user);
                $entityManager->flush();
                }
            
            return $this->redirectToRoute('app_home_page');
        }
        
        return $this->render('user/edit_password.html.twig', [
            'form' => $form->createview(),
           
        ]);
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(UserRepository $userRepository): Response
    {
        $userCount = $userRepository->countAllUsers();
        
        return $this->render('homepage/index.html.twig', [
            'userCount' => $userCount,
        ]);
    }

}
