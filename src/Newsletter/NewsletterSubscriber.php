<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 15/11/2018
 * Time: 10:48
 */

namespace App\Newsletter;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class NewsletterSubscriber implements EventSubscriberInterface
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse'
        ];
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        # On s'assure que la requête vient bien de l'utilisateur
        if (!$event->isMasterRequest() || $event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        # Incrémentation du nombre de pages visitées pa rmon utilisateur
        $this->session->set('countVisitedPages', $this->session->set('countVisitedPages', 0) + 1);

        # envoyer la modale à l'utilisateur
        if ($this->session->get('countVisitedPages') === 3) {
            $this->session->set('inviteUserModal', true);
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        # On s'assure que la requête vient bien de l'utilisateur
        if (!$event->isMasterRequest() || $event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        # on passe à false 'inviteUserModal'
            $this->session->set('inviteUserModal', false);

    }
}