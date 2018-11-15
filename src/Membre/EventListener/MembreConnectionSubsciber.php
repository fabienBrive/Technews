<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 15/11/2018
 * Time: 14:12
 */

namespace App\Membre;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;


class MembreConnectionSubsciber implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityLogin'
        ];
    }

    public function onSecurityLogin(InteractiveLoginEvent $event)
    {
        $membre = $event->getAuthenticationToken()->getUser();

        $membre->setDerniereConnection(new \DateTime());

        $this->em->persist($membre);
        $this->em->flush();
    }
}