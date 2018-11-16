<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 15/11/2018
 * Time: 15:03
 */

namespace App\Membre\Event;

/**
 * Sert à définir nos évenements
 * Class MembreEvents
 * @package App\Membre
 */
final class MembreEvents
{
    public const MEMBRE_CREATED = 'membre.created';
    public const MEMBRE_UPDATED = 'membre.updated';
    public const MEMBRE_DELETED = 'membre.deleted';
}