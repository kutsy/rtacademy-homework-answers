<?php

declare( strict_types=1 );

// version 1 //////////////////////////////////////////////////////////////////

$filepath_cities_csv  = './data/cities.csv';
$filepath_cities_html = './data/cities.html';

if( file_exists( $filepath_cities_csv ) )
{
    if( $filehandle_csv = fopen( $filepath_cities_csv, 'r' ) )
    {
        $cities_big = [];

        // зчитуємо весь файл по рядках, автоматично перетворюючи рядок в масив
        while( ( $row = fgetcsv( $filehandle_csv, 0, ',' ) ) !== false )
        {
            // якщо відсутній ключ №4 - використовувати значення за замовчуванням - 0
            if( (int)( $row[4] ?? 0 ) >= 1000000 )              // перевірка на міста з населенням більше 1 млн
            {
                $cities_big[] = $row;
            }
        }

        fclose( $filehandle_csv );
    }
    else
    {
        die( "Помилка: файл $filepath_cities_csv не відкрито" );
    }

    // перевірка на заповненість масиву великих міст
    if( !empty( $cities_big ) )
    {
        // відкриваємо файл для запису
        // УВАГА. Під Лінукс необхідно встановити для папки, в якій буде знаходитися файл дозволи 777 (# chmod 777 data)
        if( $filehandle_html = fopen( $filepath_cities_html, 'w' ) )
        {
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

            foreach( $cities_big as $city )
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

            // переходимо на цю сторінку
            header( 'Location: ' . $filepath_cities_html );
        }
        else
        {
            die( "Помилка: файл $filepath_cities_html не відкрито для запису" );
        }
    }
    else
    {
        die( 'Помилка: не знайдено міст з населенням більше 1 млн' );
    }
}
else
{
    die( "Помилка: файл $filepath_cities_csv не існує" );
}