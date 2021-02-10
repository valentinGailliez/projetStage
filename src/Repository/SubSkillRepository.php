<?php

namespace App\Repository;

use App\Entity\SubSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubSkill|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubSkill|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubSkill[]    findAll()
 * @method SubSkill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubSkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubSkill::class);
    }

    // /**
    //  * @return SubSkill[] Returns an array of SubSkill objects
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
    public function findOneBySomeField($value): ?SubSkill
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
