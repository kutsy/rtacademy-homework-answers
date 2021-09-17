<?php

declare( strict_types=1 );

spl_autoload_register(
    function( $class )
    {
        require_once( __DIR__ . '/' . str_replace( '\\', '/', $class ) . '.php' );
    }
);

// запуск сесії з параметрами
\lib\Session::start();

// деавторизація користувача
\lib\Session::deauthorize();

// переходимо на першу сторінку
header( 'Location: ./index.php' );
