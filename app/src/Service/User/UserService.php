<?php

namespace App\Service\User;

use App\Entity\User;
use App\Entity\UserRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserService
{
    private $em;
    private $security;
    
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    /**
     * Return a list of user roles depending on current user role
     * 
     * @return Array list of roles
     * 
     */
    public function getRolesList(): Array
    {
        $roles = $this->em->getRepository(UserRole::class)->findAll();

        $rolesCodeLibelle = [];

        
        foreach ($roles as $role) 
        {
            $rolesCodeLibelle[$role->getLibelle()] = $role->getCode();
        }
        
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return ['plouf' => 'plouf'];
            return $rolesCodeLibelle;
        }

        unset($rolesCodeLibelle['Admin']);

        if ($this->security->isGranted('ROLE_RESTAURATEUR')) {
            return $rolesCodeLibelle;
        }

        unset($rolesCodeLibelle['Restaurateur']);

        return $rolesCodeLibelle;
    }
}
