<?php

namespace App\Repository;

use App\Entity\PostCover;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostCover|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostCover|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostCover[]    findAll()
 * @method PostCover[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCoverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostCover::class);
    }

    // /**
    //  * @return PostCover[] Returns an array of PostCover objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostCover
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
