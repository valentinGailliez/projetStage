<?php

namespace App\Repository;

use App\Entity\ApplicationField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApplicationField|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApplicationField|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApplicationField[]    findAll()
 * @method ApplicationField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationFieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApplicationField::class);
    }

    // /**
    //  * @return ApplicationField[] Returns an array of ApplicationField objects
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
    public function findOneBySomeField($value): ?ApplicationField
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
