<?php

namespace App\Repository;

use App\Entity\WebsiteMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WebsiteMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebsiteMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebsiteMenu[]    findAll()
 * @method WebsiteMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebsiteMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebsiteMenu::class);
    }

    // /**
    //  * @return WebsiteMenu[] Returns an array of WebsiteMenu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WebsiteMenu
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
