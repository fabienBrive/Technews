<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 15/11/2018
 * Time: 15:09
 */

namespace App\Membre\Event;


use App\Entity\Membre;
use Symfony\Component\EventDispatcher\Event;

class MembreEvent extends Event
{
    private $membre;

    /**
     * MembreEvent constructor.
     * @param $membre
     */
    public function __construct(Membre $membre)
    {
        $this->membre = $membre;
    }

    /**
     * @return mixed
     */
    public function getMembre(): Membre
    {
        return $this->membre;
    }
}