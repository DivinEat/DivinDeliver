<?php

namespace App\DataFixtures;

use App\Entity\UserRole;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserRoleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $role = (new UserRole())
            ->setCode('ROLE_ADMIN')
            ->setLibelle('Admin');
        $manager->persist($role);
        
        $role = (new UserRole())
            ->setCode('ROLE_WAITER')
            ->setLibelle('Waiter');
        $manager->persist($role);

        $role = (new UserRole())
            ->setCode('ROLE_RESTAURATEUR')
            ->setLibelle('Restaurateur');
        $manager->persist($role);

        $manager->flush();
    }
}