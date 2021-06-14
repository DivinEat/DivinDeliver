<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Store;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class StoreFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $userRepository = $manager->getRepository(User::class);

        for ($i = 1; $i <= 10; $i++) {
            $object = (new Store())
                ->setName($faker->name)
                ->addUser($userRepository->find(1))
                ->addUser($userRepository->find(2));
            $manager->persist($object);

            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
