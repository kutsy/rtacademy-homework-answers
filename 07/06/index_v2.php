<?php

declare( strict_types=1 );

// version 2 //////////////////////////////////////////////////////////////////

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
            $cities_big[] = $row;
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

function createCitiesHTMLFile( string $filepath_cities_html, array $cities ) : void
{
    // відкриваємо файл для запису
    // УВАГА. Під Лінукс необхідно встановити для папки, в якій буде знаходитися файл дозволи 777 (# chmod 777 data)
    $filehandle_html = fopen( $filepath_cities_html, 'w' );

    if( !$filehandle_html )
    {
        die( "Помилка: файл $filepath_cities_html не відкрито для запису" );
    }

    $html_header = '
        <!DOCTYPE html>
        <html lang="uk">
            <head>
                <meta charset="utf-8">
                <title>#7.6</title>
            </head>
            <body>
                <table>
                    <tr>
                        <th>Місто</th>
                        <th>Країна</th>
                        <th>Населення</th>
                        <th>Координати</th>
                    </tr>';

    $html_footer = '
                </table>
            </body>
        </html>';

    fwrite( $filehandle_html, $html_header );

    foreach( $cities as $city )
    {
        fwrite(
            $filehandle_html,
            '<tr>
                <td>' . ( $city[0] ?? '' ) . '</td>
                <td>' . ( $city[3] ?? '' ) . '</td>
                <td>' . ( $city[4] ?? 0 ) . '</td>
                <td>' . ( $city[1] ?? '' ) . ', ' . ( $city[2] ?? '' ) . '</td>
            </tr>'
        );
    }

    fwrite( $filehandle_html, $html_footer );

    fclose( $filehandle_html );

    // встановлюємо дозволи
    chmod( $filepath_cities_html, 0644 );
}

function main( string $filepath_cities_csv, string $filepath_cities_html ) : void
{
    $cities_array = getBigCitiesFromCSVFile( $filepath_cities_csv );

    // створюємо результуючий HTML файл
    createCitiesHTMLFile( $filepath_cities_html, $cities_array );

    // переходимо на цю сторінку
    header( 'Location: ' . $filepath_cities_html );
}

// Запуск головної функції з параметрами
main(
    './data/cities.csv',
    './data/cities.html'
);
