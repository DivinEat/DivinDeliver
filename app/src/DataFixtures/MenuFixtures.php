<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Item;
use App\Entity\Menu;
use App\Entity\Store;
use App\Entity\Category;
use App\DataFixtures\StoreFixtures;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MenuFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $categoryRepository = $manager->getRepository(Category::class);
        $storeRepository = $manager->getRepository(Store::class);

        for ($i = 1; $i <= 10; $i++) {
            $object = (new Menu())
                ->setTitle($faker->firstName())
                ->addCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE))
                ->setStore($this->getReference(StoreFixtures::STORE_REFERENCE))
            ;
            $manager->persist($object);

            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            StoreFixtures::class,
            CategoryFixtures::class
        ];
    }
}