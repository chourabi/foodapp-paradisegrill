<?php

namespace App\Repository;

use App\Entity\ClientsReviews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientsReviews|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientsReviews|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientsReviews[]    findAll()
 * @method ClientsReviews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientsReviewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientsReviews::class);
    }

    // /**
    //  * @return ClientsReviews[] Returns an array of ClientsReviews objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClientsReviews
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
