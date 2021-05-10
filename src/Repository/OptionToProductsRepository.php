<?php

namespace App\Repository;

use App\Entity\OptionToProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OptionToProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionToProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionToProducts[]    findAll()
 * @method OptionToProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionToProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionToProducts::class);
    }

    // /**
    //  * @return OptionToProducts[] Returns an array of OptionToProducts objects
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
    public function findOneBySomeField($value): ?OptionToProducts
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
