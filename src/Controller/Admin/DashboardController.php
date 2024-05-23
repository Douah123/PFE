<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\IsGranted;
use App\Entity\User;
Use App\Repository\UserRepository;






class DashboardController extends AbstractDashboardController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    #[Route('/admin', name: 'admin_index')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        if (!$this->getUser()) {
           
            return $this->redirectToRoute('app_login');
        }
        $userCount = $this->userRepository->countAllUsers();
        $loggedInUsersCount = $this->userRepository->countLoggedInUsers();
        return $this->render('admin/dashboard.html.twig', [
            'userCount' => $userCount,
            'loggedInUsersCount' => $loggedInUsersCount,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
           
            
        ->setTitle('<img src="/assets/img/logo/logo3.png" >');

            
    }
   

    public function configureMenuItems(): iterable
    {
        
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home')->setCssClass('mb-3');
        yield MenuItem::linkToCrud('Gérer utilisateurs', 'fa-solid fa-user', User::class)->setController(UserCrudController::class)->setCssClass('mb-3');
        yield MenuItem::linkToCrud('Gérer Offres', 'fa-solid fa-briefcase', Job::class)->setController(JobCrudController::class)->setCssClass('mb-3');
        yield MenuItem::linkToCrud('Gérer Categories', 'fa-solid fa-layer-group', Job::class)->setController(CategoryCrudController::class)->setCssClass('mb-3');
        yield MenuItem::linkToRoute('Retourner au site', 'fa-solid fa-right-from-bracket', 'app_home_page');
        
    }

    
}
