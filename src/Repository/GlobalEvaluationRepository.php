<?php

namespace App\Repository;

use App\Entity\GlobalEvaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GlobalEvaluation|null find($id, $lockMode = null, $lockVersion = null)
 * @method GlobalEvaluation|null findOneBy(array $criteria, array $orderBy = null)
 * @method GlobalEvaluation[]    findAll()
 * @method GlobalEvaluation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GlobalEvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GlobalEvaluation::class);
    }

    // /**
    //  * @return GlobalEvaluation[] Returns an array of GlobalEvaluation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GlobalEvaluation
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
