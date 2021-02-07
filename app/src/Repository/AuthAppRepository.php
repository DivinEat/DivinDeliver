<?php


namespace App\Repository;


use App\Entity\AuthApp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AuthApp|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthApp|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthApp[]    findAll()
 * @method AuthApp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthAppRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthApp::class);
    }
}