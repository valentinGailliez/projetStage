<?php

namespace App\Repository;

use App\Entity\SubDay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubDay[]    findAll()
 * @method SubDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubDayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubDay::class);
    }

    // /**
    //  * @return SubDay[] Returns an array of SubDay objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubDay
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
