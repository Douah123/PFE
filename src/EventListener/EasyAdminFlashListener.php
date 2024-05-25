<?php
// src/EventListener/EasyAdminFlashListener.php
namespace App\EventListener;

use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class EasyAdminFlashListener implements EventSubscriberInterface
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => 'onAfterEntityPersisted',
            AfterEntityUpdatedEvent::class => 'onAfterEntityUpdated',
            AfterEntityDeletedEvent::class => 'onAfterEntityDeleted',
        ];
    }

    private function addFlash(string $type, string $message): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $request->getSession();
        $session->getFlashBag()->add($type, $message);
    }

    public function onAfterEntityPersisted(AfterEntityPersistedEvent $event)
    {
        $this->addFlash('success', 'L\'élément a été créé avec succès !');
    }

    public function onAfterEntityUpdated(AfterEntityUpdatedEvent $event)
    {
        $this->addFlash('success', 'L\'élément a été mis à jour avec succès !');
    }

    public function onAfterEntityDeleted(AfterEntityDeletedEvent $event)
    {
        $this->addFlash('success', 'L\'élément a été supprimé avec succès !');
    }
}
