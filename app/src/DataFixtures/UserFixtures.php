<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    // const PWD = test
    private const PWD = '$argon2id$v=19$m=65536,t=4,p=1$rKB0PNfQjtI/ZBgy+Ia98Q$YvF9EZJ2pQuRFugi9BibV68gjAB8tRqAcbXYjLpnjKY';
    public const ADMIN_USER_REFERENCE = 'admin-user';
    public const RESTAURATEUR_USER_REFERENCE = 'restaurateur-user';

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $userAdmin = (new User())
            ->setFirstname("Admin")
            ->setLastname("Admin")
            ->setEmail($faker->email)
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword(self::PWD)
            ->setIsValid(true)
        ;
        $manager->persist($userAdmin);
        $manager->flush();
        $this->addReference(self::ADMIN_USER_REFERENCE, $userAdmin);

        $userRestaurateur = (new User())
            ->setFirstname("Restaurateur")
            ->setLastname("Restaurateur")
            ->setEmail($faker->email)
            ->setRoles(["ROLE_RESTAURATEUR"])
            ->setPassword(self::PWD)
            ->setIsValid(true)
        ;
        $manager->persist($userRestaurateur);
        $manager->flush();
        $this->addReference(self::RESTAURATEUR_USER_REFERENCE, $userRestaurateur);

        for ($i = 1; $i <= 10; $i++) {
            $object = (new User())
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setRoles(["ROLE_ADMIN"])
                ->setPassword(self::PWD)
                ->setIsValid(true)
            ;
            $manager->persist($object);

            $object = (new User())
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setRoles(["ROLE_RESTAURATEUR"])
                ->setPassword(self::PWD)
                ->setIsValid(true)
            ;
            $manager->persist($object);

            $object = (new User())
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setRoles(["ROLE_WAITER"])
                ->setPassword(self::PWD)
                ->setIsValid(true)
            ;
            $manager->persist($object);

            $manager->flush();
        }
    }
}