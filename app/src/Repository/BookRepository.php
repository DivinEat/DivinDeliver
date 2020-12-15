<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @param string $q
     * @param bool $tag
     * @return int|mixed|string
     */
    public function search(string $q, bool $tag = false)
    {
        $query = $this->createQueryBuilder('b')
            ->andWhere('b.name LIKE :q')
            ->orWhere('b.description LIKE :q')
            ->setParameter('q', "%$q%")
            ->orderBy('b.id', 'ASC')
        ;

        if ($tag) {
            $query
                ->leftJoin('b.tags', 't')
                ->orWhere('t.name LIKE :q')
            ;
        }

        return  $query->getQuery()->getResult();
    }
}
