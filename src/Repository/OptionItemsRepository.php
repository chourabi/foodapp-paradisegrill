<?php

namespace App\Repository;

use App\Entity\OptionItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OptionItems|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionItems|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionItems[]    findAll()
 * @method OptionItems[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionItems::class);
    }

    // /**
    //  * @return OptionItems[] Returns an array of OptionItems objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OptionItems
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
