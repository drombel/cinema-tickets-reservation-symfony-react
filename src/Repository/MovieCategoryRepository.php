<?php

namespace App\Repository;

use App\Entity\MovieCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovieCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieCategory[]    findAll()
 * @method MovieCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieCategory::class);
    }

    // /**
    //  * @return MovieCategory[] Returns an array of MovieCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MovieCategory
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
