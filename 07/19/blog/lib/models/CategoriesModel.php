<?php

declare( strict_types=1 );

namespace lib\models;

class CategoriesModel
{
    /**
     * @param string $alias
     *
     * @return bool
     */
    public function existsByAlias( string $alias ) : bool
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // готуємо підготований запит з параметром :alias
            $statement = $db->prepare(
                "
                    SELECT
                        count(id) AS c
                    FROM
                        posts_categories
                    WHERE
                        alias = :alias
                "
            );

            // виконання підготованого запита з параметром :alias
            $statement->execute(
                [
                    ':alias' => $alias,
                ]
            );

            return boolval( $statement->fetch( \PDO::FETCH_ASSOC )['c'] ?? 0 );
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );
            return true;
        }
    }

    /**
     * @param string $title
     * @param string $alias
     *
     * @return bool
     */
    public function add( string $title, string $alias ) : bool
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // готуємо підготований запит з параметрами :title та :alias
            $statement = $db->prepare(
                "
                    INSERT INTO 
                        posts_categories
                        (title, alias)
                    VALUES
                        (:title, :alias);
                "
            );

            // виконання підготованого запита з параметрами :title та :alias
            $statement->execute(
                [
                    ':title' => $title,
                    ':alias' => $alias,
                ]
            );

            return true;
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );
            return false;
        }
    }

    /**
     * @param int    $id
     * @param string $title
     * @param string $alias
     *
     * @return bool
     */
    public function edit( int $id, string $title, string $alias ) : bool
    {
        // TODO
    }

    /**
     * @param int $id
     *
     * @return \lib\entities\Category|null
     */
    public function getSingle( int $id ) : ?\lib\entities\Category
    {
        // TODO
    }
}