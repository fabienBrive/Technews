<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 13/11/2018
 * Time: 11:10
 */

namespace App\Membre;


use App\Entity\Membre;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MembreFactory
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Création d'un objet Article à partier d'un article Request.
     * Pour insertion en BDD.
     * @param MembreRequest $request
     * @return Membre
     */
    public function createFromMembreRequest(MembreRequest $request): Membre
    {
        $membre = new Membre();
        $membre->setPrenom($request->getPrenom());
        $membre->setNom($request->getNom());
        $membre->setEmail($request->getEmail());
        $membre->setRoles($request->getRoles());
        $membre->setPassword($this->encoder->encodePassword($membre, $request->getPassword()));

        return $membre;

    }
}