<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Store;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class StoreFixtures extends Fixture implements DependentFixtureInterface
{

    public const STORE_REFERENCE = 'store';

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $store = (new Store())
            ->setName("StoreReference")
            ->addUser($this->getReference(UserFixtures::ADMIN_USER_REFERENCE))
            ->addUser($this->getReference(UserFixtures::RESTAURATEUR_USER_REFERENCE))
        ;
        $manager->persist($store);
        $manager->flush();
        $this->addReference(self::STORE_REFERENCE, $store);

        for ($i = 1; $i <= 10; $i++) {
            $object = (new Store())
                ->setName($faker->name)
                ->addUser($this->getReference(UserFixtures::ADMIN_USER_REFERENCE))
                ->addUser($this->getReference(UserFixtures::RESTAURATEUR_USER_REFERENCE))
            ;
            $manager->persist($object);

            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
