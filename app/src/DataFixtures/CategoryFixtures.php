<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Store;
use App\Entity\Category;
use App\DataFixtures\StoreFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{

    public const CATEGORY_REFERENCE = 'category';

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $category = (new Category())
            ->setTitle("CategoryReference")
            ->setSubtitle("CategoryReference")
            ->setStore($this->getReference(StoreFixtures::STORE_REFERENCE))
        ;
        $manager->persist($category);
        $manager->flush();
        $this->addReference(self::CATEGORY_REFERENCE, $category);

        for ($i = 1; $i <= 10; $i++) {
            $object = (new Category())
                ->setTitle($faker->firstName())
                ->setSubtitle($faker->lastName())
                ->setStore($this->getReference(StoreFixtures::STORE_REFERENCE))
            ;
            $manager->persist($object);

            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            StoreFixtures::class
        ];
    }
}