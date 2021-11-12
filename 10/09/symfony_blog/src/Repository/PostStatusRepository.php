<?php

namespace App\Repository;

use App\Entity\PostStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostStatus[]    findAll()
 * @method PostStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostStatus::class);
    }
}
