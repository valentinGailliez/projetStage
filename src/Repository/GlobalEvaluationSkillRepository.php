<?php

namespace App\Repository;

use App\Entity\GlobalEvaluationSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GlobalEvaluationSkill|null find($id, $lockMode = null, $lockVersion = null)
 * @method GlobalEvaluationSkill|null findOneBy(array $criteria, array $orderBy = null)
 * @method GlobalEvaluationSkill[]    findAll()
 * @method GlobalEvaluationSkill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GlobalEvaluationSkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GlobalEvaluationSkill::class);
    }

    // /**
    //  * @return GlobalEvaluationSkill[] Returns an array of GlobalEvaluationSkill objects
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
    public function findOneBySomeField($value): ?GlobalEvaluationSkill
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
