<?php
// src/EventSubscriber/LogoutSubscriber.php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\User;

class LogoutSubscriber implements EventSubscriberInterface
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function onLogout(LogoutEvent $event): void
    {
        $this->logger->info('Logout event triggered');

        $token = $event->getToken();
        if ($token === null) {
            $this->logger->warning('Logout event does not have a token.');
            return;
        }

        $user = $token->getUser();

        if ($user instanceof User) {
            $this->logger->info('User logged out: ' . $user->getLastname());
            $user->setLastLogout(new \DateTime());
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->logger->info('Logout time updated for user: ' . $user->getLastname());
        } else {
            $this->logger->warning('User is not an instance of App\Entity\User');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogout',
        ];
    }
}
