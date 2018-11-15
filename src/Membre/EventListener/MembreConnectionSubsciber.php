<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 15/11/2018
 * Time: 14:12
 */

namespace App\Membre;


use App\Entity\Membre;
use App\Entity\Newsletter;
use App\Membre\Event\MembreEvent;
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
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
            MembreEvents::MEMBRE_CREATED => 'onMembreCreated'
        ];
    }

    public function onMembreCreated(MembreEvent $event)
    {
        $newsletter = new Newsletter();
        $newsletter->setEmail($event->getMembre()->getEmail());
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $membre = $event->getAuthenticationToken()->getUser();

        if ($membre instanceof Membre) {

            # Mise à jour du timestamp
            $membre->setDerniereConnection();

            # Sauvegarde en BDD
            $this->em->flush();
        }
    }
}