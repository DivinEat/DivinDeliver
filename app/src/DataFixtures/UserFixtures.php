<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    // const PWD = test
    private const PWD = '$argon2id$v=19$m=65536,t=4,p=1$rKB0PNfQjtI/ZBgy+Ia98Q$YvF9EZJ2pQuRFugi9BibV68gjAB8tRqAcbXYjLpnjKY';

    public function load(ObjectManager $manager)
    {
        $object = (new User())
            ->setEmail("dev@technique")
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword(self::PWD)
        ;
        $manager->persist($object);

        $manager->flush();
    }
}