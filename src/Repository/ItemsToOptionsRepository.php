<?php

namespace App\Repository;

use App\Entity\ItemsToOptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemsToOptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemsToOptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemsToOptions[]    findAll()
 * @method ItemsToOptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemsToOptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemsToOptions::class);
    }

    // /**
    //  * @return ItemsToOptions[] Returns an array of ItemsToOptions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemsToOptions
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
