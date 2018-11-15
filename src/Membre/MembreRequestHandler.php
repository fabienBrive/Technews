<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 13/11/2018
 * Time: 11:03
 */

namespace App\Membre;


use App\Entity\Membre;
use Doctrine\Common\Persistence\ObjectManager;

class MembreRequestHandler
{
    private $manager, $membreFactory;

    /**
     * MembreRequestHandler constructor.
     * @param $manager
     * @param $membreFactory
     */
    public function __construct(ObjectManager $manager, MembreFactory $membreFactory)
    {
        $this->manager = $manager;
        $this->membreFactory = $membreFactory;
    }


    public function handle(MembreRequest $request): Membre
    {
        #création de l'objet membre
        $membre = $this->membreFactory->createFromMembreRequest($request);

        #on sauvegarde dans la BDD le nouveau membre
        $this->manager->persist($membre);
        $this->manager->flush();

        # On retourne le nouvel utilisateur
        return $membre;
    }
}