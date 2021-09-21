<?php

declare( strict_types=1 );

namespace lib;

class DbConnection
{
    private const HOST       = 'rtacademy_database'; // '127.0.0.1';
    private const PORT       = 3306;
    private const DBNAME     = 'helloworld';
    private const DBUSER     = 'helloworld';
    private const DBPASSWORD = 'helloworld';

    private static ?\PDO $_db = null;

    /**
     * Метод підʼєднується до БД за допомогою PDO тільки у випадку якщо ще не було виконано підʼєднання
     *
     * @throws \PDOException
     * @return \PDO
     */
    public static function getConnection(): \PDO
    {
        if( empty( self::$_db ) )
        {
            // підʼєднуємось до БД
            self::$_db = new \PDO(
                'mysql:host=' . self::HOST . ';port=' . self::PORT . ';dbname=' . self::DBNAME,
                self::DBUSER,
                self::DBPASSWORD
            );

            // встановлюємо режим викиду PDOException при виникненні помилки
            self::$_db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        }

        return self::$_db;
    }
}
