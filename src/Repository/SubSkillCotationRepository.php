<?php

namespace App\Repository;

use App\Entity\SubSkillCotation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubSkillCotation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubSkillCotation|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubSkillCotation[]    findAll()
 * @method SubSkillCotation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubSkillCotationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubSkillCotation::class);
    }

    // /**
    //  * @return SubSkillCotation[] Returns an array of SubSkillCotation objects
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
    public function findOneBySomeField($value): ?SubSkillCotation
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
