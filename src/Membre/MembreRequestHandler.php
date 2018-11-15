<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 13/11/2018
 * Time: 11:03
 */

namespace App\Membre;


use App\Entity\Membre;
use App\Membre\Event\MembreEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MembreRequestHandler
{
    private $manager, $membreFactory, $dispacher;

    /**
     * MembreRequestHandler constructor.
     * @param ObjectManager $manager
     * @param EventDispatcherInterface $dispatcher
     * @param MembreFactory $membreFactory
     */
    public function __construct(ObjectManager $manager,
                                EventDispatcherInterface $dispatcher,
                                MembreFactory $membreFactory)
    {
        $this->manager = $manager;
        $this->membreFactory = $membreFactory;
        $this->dispacher = $dispatcher;
    }


    public function handle(MembreRequest $request): Membre
    {
        #création de l'objet membre
        $membre = $this->membreFactory->createFromMembreRequest($request);

        #on sauvegarde dans la BDD le nouveau membre
        $this->manager->persist($membre);
        $this->manager->flush();

        # On emet notre événement
        $this->dispacher->dispatch(MembreEvents::MEMBRE_CREATED, new MembreEvent($membre));

        # On retourne le nouvel utilisateur
        return $membre;
    }
}