<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Store;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function getOrderByUser(User $user)
    {
        $store = $user->getStores()->first();

        if (!$store instanceof Store)
            return [];

        return $store->getOrders()->getValues();
    }
}
