<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Item;
use App\Entity\Store;
use App\Entity\Category;
use App\DataFixtures\StoreFixtures;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ItemFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $categoryRepository = $manager->getRepository(Category::class);
        $storeRepository = $manager->getRepository(Store::class);

        for ($i = 1; $i <= 10; $i++) {
            $object = (new Item())
                ->setTitle($faker->firstName())
                ->setPriceInfo(random_int(2, 20))
                ->setCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE))
                ->setStore($this->getReference(StoreFixtures::STORE_REFERENCE))
            ;
            $manager->persist($object);

            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            StoreFixtures::class
        ];
    }
}