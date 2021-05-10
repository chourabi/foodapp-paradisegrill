<?php

namespace App\Repository;

use App\Entity\TableOrdre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TableOrdre|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableOrdre|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableOrdre[]    findAll()
 * @method TableOrdre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableOrdreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableOrdre::class);
    }

    // /**
    //  * @return TableOrdre[] Returns an array of TableOrdre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TableOrdre
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
