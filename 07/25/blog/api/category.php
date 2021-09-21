<?php

declare( strict_types=1 );

spl_autoload_register(
    function( $class )
    {
        require_once( __DIR__ . '/../' . str_replace( '\\', '/', $class ) . '.php' );
    }
);

// отримуємо ID запису
$categoryId = intval( preg_replace( '#[^0-9]#', '', $_GET['id'] ?? '0' ) );

// створення екземпляру класа CategoryApiController
$categoryApiController = new \lib\controllers\CategoryApiController();

// визначаємо дію, відносно HTTP методу
switch( $_SERVER['REQUEST_METHOD'] ?? 'GET' )
{
    case 'GET':
    default:
        if( empty( $categoryId ) )
        {
            $response = $categoryApiController->getList();
        }
        else
        {
            $response = $categoryApiController->getSingle( $categoryId );
        }
        break;

    case  'POST':
        $response = $categoryApiController->create();
        break;

    case 'PUT':
        $response = $categoryApiController->update( $categoryId );
        break;

    case 'DELETE':
        $response = $categoryApiController->delete( $categoryId );
        break;
}

// встановлюємо заголовок JSON
header( 'Content-Type: application/json' );

// перетворюємо масив в JSON
echo( json_encode( $response ) );