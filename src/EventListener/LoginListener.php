<?php

// src/EventListener/LoginListener.php
namespace App\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\User;

class LoginListener
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $this->logger->info('Interactive login event triggered');

        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof User) {
            $this->logger->info('Setting last login for user: ' . $user->getUsername());
            $user->setLastLogin(new \DateTime());
            $this->entityManager->flush();
            $this->logger->info('Last login updated for user: ' . $user->getUsername());
        } else {
            $this->logger->warning('User is not an instance of App\Entity\User');
        }
    }
}
