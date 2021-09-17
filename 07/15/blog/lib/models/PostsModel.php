<?php

declare( strict_types=1 );

namespace lib\models;

class PostsModel
{
    /** @var int Кількість записів на одній сторінці */
    public const COUNT_PER_PAGE = 3;

    /**
     * @param int $page
     *
     * @return \lib\entities\Post[]
     */
    public function getList( int $page = 1 ) : array
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // OFFSET розраховується за такою формулою, відносно номера сторінки
            $offset = ( $page - 1 ) * self::COUNT_PER_PAGE;

            // виконуємо запит
            $statement = $db->query(
                '
                    SELECT
                        p.id,
                        p.title,
                        p.alias,
                        p.description,
                        p.publish_date,
                        p.category_id,
                        pc.title        AS category_title,
                        pc.alias        AS category_alias,
                        p.author_id,
                        u.firstname     AS author_firstname,
                        u.lastname      AS author_lastname,
                        pc2.filename    AS cover_filename,
                        pc2.alt         AS cover_alt
                    FROM
                        posts AS p
                    LEFT JOIN
                        posts_categories AS pc ON ( p.category_id = pc.id )
                    LEFT JOIN
                        users AS u ON ( p.author_id = u.id )
                    LEFT JOIN
                        posts_covers AS pc2 ON ( p.cover_id = pc2.id )
                    INNER JOIN
                        posts_statuses AS ps2 ON ( ps2.name = \'active\' AND p.status_id = ps2.id )
                    WHERE
                        p.status_id = ps2.id
                        AND
                        p.publish_date <= now()
                    ORDER BY
                        p.publish_date DESC
                    LIMIT
                        ' . self::COUNT_PER_PAGE . '
                    OFFSET
                        ' . $offset,
                \PDO::FETCH_ASSOC
            );

            $posts = [];

            foreach( $statement as $row )
            {
                // Author
                $author = new \lib\entities\Author();
                $author->setId( (int)$row['author_id'] );
                $author->setFirstName( $row['author_firstname'] );
                $author->setLastName( $row['author_lastname'] );

                // Category
                $category = new \lib\entities\Category();
                $category->setId( (int)$row['category_id'] );
                $category->setTitle( $row['category_title'] );
                $category->setAlias( $row['category_alias'] );

                // Cover
                $cover = new \lib\entities\Cover( $row['cover_filename'], $row['cover_alt'] );

                // Post
                $post = new \lib\entities\Post();
                $post->setId( (int)$row['id'] );
                $post->setTitle( $row['title'] );
                $post->setAlias( $row['alias'] );
                $post->setDescription( $row['description'] );
                $post->setAuthor( $author );
                $post->setPublishDate( $row['publish_date'] );
                $post->setCategory( $category );
                $post->setCover( $cover );

                $posts[] = $post;
            }

            return $posts;
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );

            return [];
        }
    }

    /**
     * @return int
     */
    public function getTotalCount() : int
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // виконуємо запит
            $statement = $db->query(
                "
                    SELECT
                        count(p.id) as c
                    FROM
                        posts AS p
                    INNER JOIN
                        posts_statuses AS ps2 ON ( ps2.name = 'active' AND p.status_id = ps2.id )
                    WHERE
                        p.status_id = ps2.id
                        AND
                        p.publish_date <= now()
                ",
                \PDO::FETCH_ASSOC
            );

            return (int) $statement->fetch( \PDO::FETCH_ASSOC )['c'] ?? 0;
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );

            return 0;
        }
    }
}