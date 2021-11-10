<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 9;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return \App\Entity\Post|null
     */
    public function getTopPost(): ?Post
    {
        return $this->findOneBy(
            [],
            [ 'publish_date' => 'DESC' ]
            // TODO: оскільки у БД відмітки "ТОП запис" не враховано структурою, повертаємо просто останній
        );
    }

    /**
     * @param int $offset
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getLatestPosts( int $offset = 0 ) : Paginator
    {
        $query =
            $this->createQueryBuilder( 'p' )
                ->innerJoin( 'p.status', 'ps' )
                ->andWhere( 'ps.name = :active' )
                ->setParameter( 'active', 'active' )
                ->orderBy( 'p.publish_date', 'DESC' )
                ->setMaxResults( self::PAGINATOR_PER_PAGE )
                ->setFirstResult( $offset )
                ->getQuery();

        return new Paginator( $query );
    }
}
