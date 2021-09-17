<?php

declare( strict_types=1 );

define( 'MAX_FILE_SIZE', 10485760 );            // константа максимального розміру файла

$error_message = '';                            // змінна з текстом помилки, якщо виникне

/**
 * Перевірка коректності завантаження файла на сервер
 *
 * @param string $name
 * @param int    $max_size
 *
 * @return bool
 */
function processUploadedFile( string $name, int $max_size = MAX_FILE_SIZE ): bool
{
    // для звернення до змінної $error_message (і встановлення інших її значень) за межами функції необхідно використовувати "global"
    global $error_message;

    // перевірка на відправку файла
    if( empty( $_FILES[ $name ] ) )
    {
        $error_message = 'Помилка. Необхідно завантажити файл.';
        return false;
    }

    // перевірка успішне завантаження файлу на сервер
    if( $_FILES[ $name ]['error'] !== UPLOAD_ERR_OK )
    {
        $error_message = 'Сталася помилка під час завантаження файлу.';
        return false;
    }

    // перевірка на формат файлу
    $valid_mimetypes = [ 'text/csv', 'application/vnd.ms-excel' ];

    if( !in_array( $_FILES[ $name ]['type'], $valid_mimetypes ) )
    {
        $error_message =  'Помилка. Файл повинен мати формат CSV.';
        return false;
    }

    // перевірка на розмір файлу
    if( $_FILES[ $name ]['size'] > $max_size )
    {
        $error_message = "Помилка. Файл повинен бути менше $max_size байт.";
        return false;
    }

    return true;
}

/**
 * Отримання міст України з CSV файлу
 *
 * @param string $filepath_cities_csv
 *
 * @return array|null
 */
function getUkrainianCitiesFromCSVFile( string $filepath_cities_csv ) : ?array
{
    // для звернення до змінної $error_message (і встановлення інших її значень) за межами функції необхідно використовувати "global"
    global $error_message;

    if( !file_exists( $filepath_cities_csv ) )
    {
        $error_message = "Помилка: файл $filepath_cities_csv не існує";
        return null;
    }

    $filehandle_csv = fopen( $filepath_cities_csv, 'r' );

    if( !$filehandle_csv )
    {
        $error_message =  "Помилка: файл $filepath_cities_csv не відкрито";
        return null;
    }

    $cities_ukraine = [];

    // зчитуємо весь файл по рядках, автоматично перетворюючи рядок в масив
    while( ( $row = fgetcsv( $filehandle_csv, 0, ',' ) ) !== false )
    {
        // якщо відсутній ключ №3 - використовувати значення за замовчуванням - ""
        if( (string)( $row[3] ?? '' ) == 'Ukraine' )    // перевірка на міста з України
        {
            if( (int)( $row[4] ?? 0 ) > 0 )             // відкинемо одразу міста у яких "0" жителів
            {
                $cities_ukraine[] =
                [
                    'city'       => (string)( $row[0] ?? '' ),
                    'country'    => (string)( $row[3] ?? '' ),
                    'population' => (int)( $row[4] ?? 0 ),
                    'latitude'   => (float)( $row[1] ?? 0 ),
                    'longitude'  => (float)( $row[2] ?? 0 ),
                ];
            }
        }
    }

    fclose( $filehandle_csv );

    // перевірка на заповненість масиву великих міст
    if( empty( $cities_ukraine ) )
    {
        $error_message = "Помилка: не знайдено міст з України";
        return null;
    }

    return $cities_ukraine;
}

/**
 * Сортування асоціативного масиву за ключем $key
 *
 * @param array  $cities
 * @param string $key
 *
 * @return array
 */
function sortCitiesByName( array $cities, string $key ): array
{
    $names = array_column( $cities, $key );

    // https://www.php.net/manual/ru/function.array-multisort.php
    array_multisort( $names, SORT_ASC, $cities );

    return $cities;
}

/**
 * Збереження HTML-файла з містами
 *
 * @param string $filepath_cities_html
 * @param array  $cities
 *
 * @return bool
 */
function createCitiesHTMLFile( string $filepath_cities_html, array $cities ) : bool
{
    // для звернення до змінної $error_message (і встановлення інших її значень) за межами функції необхідно використовувати "global"
    global $error_message;

    // відкриваємо файл для запису
    // УВАГА. Під Лінукс необхідно встановити для папки, в якій буде знаходитися файл дозволи 777 (# chmod 777 data)
    $filehandle_html = fopen( $filepath_cities_html, 'w' );

    if( !$filehandle_html )
    {
        $error_message = "Помилка: файл $filepath_cities_html не відкрито для запису";
        return false;
    }

    $html_header = '
        <!DOCTYPE html>
        <html lang="uk">
            <head>
                <meta charset="utf-8">
                <title>#7.8</title>
            </head>
            <body>
                <table>
                    <tr>
                        <th>Місто</th>
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
                <td>' . $city['city'] . '</td>
                <td>' . $city['population'] . '</td>
                <td>' . $city['latitude'] . ', ' . $city['longitude'] . '</td>
            </tr>'
        );
    }

    fwrite( $filehandle_html, $html_footer );

    fclose( $filehandle_html );

    // встановлюємо дозволи
    chmod( $filepath_cities_html, 0644 );

    return true;
}

/**
 * Перехід на нову адресу
 *
 * @param string $location
 */
function redirect( string $location ) : void
{
    header( 'Location: ' . $location );
}

/**
 * Головна функція
 */
function main() : void
{
    // перевірка на "перше відкриття" сторінки, коли ще не заповнена форма
    if( empty( $_POST ) )
    {
        return;
    }

    $input_name = 'userfile';                   // значення атрибута "name" для <input type="file">

    // перевірка на коректність надсилання файлу
    if( ! processUploadedFile( $input_name ) )
    {
        // виникла помилка
        return;
    }

    // отримання масиву міст з України
    $cities = getUkrainianCitiesFromCSVFile( $_FILES[ $input_name ]['tmp_name'] ?? '' );

    if( empty( $cities ) )          // тут "NULL" та "[]" спрацюють однаково
    {
        // виникла помилка
        return;
    }

    // сортування асоціативного масиву за ключем "city"
    $cities = sortCitiesByName( $cities, 'city' );

    $filepath_cities_html = './data/cities_ukraine.html';

    // створюємо результуючий HTML файл
    if( ! createCitiesHTMLFile( $filepath_cities_html, $cities ) )
    {
        // виникла помилка
        return;
    }

    // переходимо на цю сторінку
    redirect( $filepath_cities_html );
}

// Запуск головної функції
main();

?><!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <title>#7.8</title>
    <style>
        body {
            font: normal 1rem/1.5rem Verdana,sans-serif;
            color: #000;
            margin: 0;
            padding: 0;
        }
        main {
            width: 40rem;
            margin: calc(50vh - 8rem) auto 0 auto;
        }
            main form ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }
                main form ul li {
                    margin: 0 0 1.5rem 0;
                }
                main form ul li.error {
                    font-size: 0.9rem;
                    border-left: 0.5rem solid #800000;
                    background: #fff5f5;
                    padding: 0.5rem 0 0.5rem 0.5rem;
                }
                    main form ul li label {
                        color: #666;
                    }
    </style>
</head>
<body>

<main>
    <form enctype="multipart/form-data" method="POST">
        <ul>
            <?php

            // відображаємо помилку, якщо вона встановлена в щось, окрім ""
            if( !empty( $error_message ) )
            {
                echo( '<li class="error">' . $error_message . '</li>' );
            }

            ?>
            <li>
                <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_FILE_SIZE ?>" />
                <label for="city">Файл з містами (CSV):</label>
                <input name="userfile" type="file" placeholder="Оберіть файл з містами у форматі CSV" />
            </li>
            <li>
                <button type="submit">Надіслати</button>
            </li>
        </ul>
    </form>
</main>

</body>
</html>