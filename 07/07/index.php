<?php

declare( strict_types=1 );

/**
 * @param string $filepath_cities_csv
 * @param int    $population_threshold
 *
 * @return array
 */
function getBigCitiesFromCSVFile( string $filepath_cities_csv, int $population_threshold = 1000000 ) : array
{
    if( !file_exists( $filepath_cities_csv ) )
    {
        die( "Помилка: файл $filepath_cities_csv не існує" );
    }

    $filehandle_csv = fopen( $filepath_cities_csv, 'r' );

    if( !$filehandle_csv )
    {
        die( "Помилка: файл $filepath_cities_csv не відкрито" );
    }

    $cities_big = [];

    // зчитуємо весь файл по рядках, автоматично перетворюючи рядок в масив
    while( ( $row = fgetcsv( $filehandle_csv, 0, ',' ) ) !== false )
    {
        // якщо відсутній ключ №4 - використовувати значення за замовчуванням - 0
        if( (int)( $row[4] ?? 0 ) >= $population_threshold )    // перевірка на міста з населенням більше $population_threshold
        {
            $cities_big[] =
            [
                'city'       => (string)( $row[0] ?? '' ),
                'country'    => (string)( $row[3] ?? '' ),
                'population' => (int)( $row[4] ?? 0 ),
                'latitude'   => (float)( $row[1] ?? 0 ),
                'longitude'  => (float)( $row[2] ?? 0 ),
            ];
        }
    }

    fclose( $filehandle_csv );

    // перевірка на заповненість масиву великих міст
    if( empty( $cities_big ) )
    {
        die( "Помилка: не знайдено міст з населенням більше $population_threshold жителів" );
    }

    return $cities_big;
}

/**
 * @param string $filepath_cities_json
 * @param array  $cities
 */
function createCitiesJSONFile( string $filepath_cities_json, array $cities ) : void
{
    // УВАГА. Під Лінукс необхідно встановити для папки, в якій буде знаходитися файл дозволи 777 (# chmod 777 data)

    // відкриваємо файл для запису та записуємо масив з містами у вигляді JSON
    if( ! file_put_contents( $filepath_cities_json, json_encode( $cities ) ) )
    {
        die( "Помилка: файл $filepath_cities_json не відкрито для запису" );
    }
    // встановлюємо дозволи
    chmod( $filepath_cities_json, 0644 );
}

/**
 * @param string $filepath_cities_csv
 * @param string $filepath_cities_json
 */
function main( string $filepath_cities_csv, string $filepath_cities_json ) : void
{
    $cities_array = getBigCitiesFromCSVFile( $filepath_cities_csv );

    // створюємо результуючий JSON файл
    createCitiesJSONFile( $filepath_cities_json, $cities_array );

    // переходимо на цю сторінку
    header( 'Location: ' . $filepath_cities_json );
}

// Запуск головної функції з параметрами
main(
    './data/cities.csv',
    './data/cities.json'
);
