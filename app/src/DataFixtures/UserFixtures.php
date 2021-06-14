<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    // const PWD = test
    private const PWD = '$argon2id$v=19$m=65536,t=4,p=1$rKB0PNfQjtI/ZBgy+Ia98Q$YvF9EZJ2pQuRFugi9BibV68gjAB8tRqAcbXYjLpnjKY';

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $object = (new User())
                ->setEmail($faker->email)
                ->setRoles(["ROLE_ADMIN"])
                ->setPassword(self::PWD)
            ;
            $manager->persist($object);

            $object = (new User())
                ->setEmail($faker->email)
                ->setRoles(["ROLE_RESTAURATEUR"])
                ->setPassword(self::PWD)
            ;
            $manager->persist($object);

            $object = (new User())
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setRoles(["ROLE_WAITER"])
                ->setPassword(self::PWD)
            ;
            $manager->persist($object);

            $manager->flush();
        }
    }
}