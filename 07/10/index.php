<?php

declare( strict_types=1 );

class CitiesParser
{
    /** @var int Константа максимального розміру файла */
    public const MAX_FILE_SIZE                = 10485760;

    /** @var string Текст помилки */
    protected string $_error_message          = '';

    /** @var string Значення поля name для <input type="file"> */
    protected string $_upload_file_input_name = '';

    /** @var string Шлях до завантаженного файла в тимчасовій директорії */
    protected string $_upload_file_tmpname    = '';

    /** @var string Країна */
    protected string $_selected_country       = '';

    /** @var array Масив з містами по обраній країні для подальної обробки */
    protected array  $_cities                 = [];

    /** @var string Шлях до результуючого HTML-файла */
    protected string $_filepath_cities_html   = '';

    /**
     * Конструктор CitiesParser
     *
     * @param string $upload_file_input_name
     * @param string $selected_country
     * @param string $filepath_cities_html
     */
    public function __construct( string $upload_file_input_name, string $selected_country, string $filepath_cities_html )
    {
        $this->_upload_file_input_name = $upload_file_input_name;
        $this->_selected_country       = $selected_country;
        $this->_filepath_cities_html   = $filepath_cities_html;
    }

    /**
     * Ініціалізація запуску головної функції
     *
     * @throws \Exception
     * @return $this
     */
    protected function _initialize() : self
    {
        // перевірка на "перше відкриття" сторінки, коли ще не заповнена форма
        if( empty( $_POST ) )
        {
            throw new Exception( '' );      // не заповнюємо текст помилки, бо відобразиться помилка у HTML
        }

        return $this;
    }

    /**
     * Перевірка коректності завантаження файла на сервер
     *
     * @throws \Exception
     * @return $this
     */
    protected function _processUploadedFile(): self
    {
        // перевірка на відправку файла
        if( empty( $_FILES[ $this->_upload_file_input_name ] ) )
        {
            throw new Exception( 'Помилка. Необхідно завантажити файл.' );
        }

        // перевірка успішне завантаження файлу на сервер
        if( $_FILES[ $this->_upload_file_input_name ]['error'] !== UPLOAD_ERR_OK )
        {
            throw new Exception( 'Сталася помилка під час завантаження файлу.' );
        }

        // перевірка на формат файлу
        $valid_mimetypes = [ 'text/csv', 'application/vnd.ms-excel' ];

        if( !in_array( $_FILES[ $this->_upload_file_input_name ]['type'], $valid_mimetypes ) )
        {
            throw new Exception( 'Помилка. Файл повинен мати формат CSV.' );
        }

        // перевірка на розмір файлу
        if( $_FILES[ $this->_upload_file_input_name ]['size'] > self::MAX_FILE_SIZE )
        {
            throw new Exception( 'Помилка. Файл повинен бути менше ' . self::MAX_FILE_SIZE . ' байт.' );
        }

        $this->_upload_file_tmpname = $_FILES[ $this->_upload_file_input_name ]['tmp_name'] ?? '';

        return $this;
    }

    /**
     * Отримання міст з CSV файлу для обраної країни
     *
     * @throws \Exception
     * @return $this
     */
    protected function _getCitiesFromCSVFile() : self
    {
        if( !file_exists( $this->_upload_file_tmpname ) )
        {
            throw new Exception( "Помилка: файл $this->_upload_file_tmpname не існує" );
        }

        $filehandle_csv = fopen( $this->_upload_file_tmpname, 'r' );

        if( !$filehandle_csv )
        {
            throw new Exception(  "Помилка: файл $this->_upload_file_tmpname не відкрито" );
        }

        $this->_cities = [];

        // зчитуємо весь файл по рядках, автоматично перетворюючи рядок в масив
        while( ( $row = fgetcsv( $filehandle_csv, 0, ',' ) ) !== false )
        {
            // якщо відсутній ключ №3 - використовувати значення за замовчуванням - ""
            if( (string)( $row[3] ?? '' ) == $this->_selected_country )    // перевірка на міста з визначеної країни
            {
                if( (int)( $row[4] ?? 0 ) > 0 )             // відкинемо одразу міста у яких "0" жителів
                {
                    $this->_cities[] =
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
        if( empty( $this->_cities ) )
        {
            throw new Exception( "Помилка: не знайдено міст з " . $this->_selected_country );
        }

        return $this;
    }

    /**
     * Сортування асоціативного масиву за ключем 'city'
     *
     * @return $this
     */
    protected function _sortCitiesByName() : self
    {
        $names = array_column( $this->_cities, 'city' );

        // https://www.php.net/manual/ru/function.array-multisort.php
        array_multisort( $names, SORT_ASC, $this->_cities );

        return $this;
    }

    /**
     * Збереження HTML-файла з містами
     *
     * @throws \Exception
     * @return $this
     */
    protected function _createCitiesHTMLFile() : self
    {
        // відкриваємо файл для запису
        // УВАГА. Під Лінукс необхідно встановити для папки, в якій буде знаходитися файл дозволи 777 (# chmod 777 data)
        $filehandle_html = fopen( $this->_filepath_cities_html, 'w' );

        if( !$filehandle_html )
        {
            throw new Exception( 'Помилка: файл '.$this->_filepath_cities_html.' не відкрито для запису' );
        }

        $html_header =
            '<!DOCTYPE html>'.
            '<html lang="uk">'.
                '<head>'.
                    '<meta charset="utf-8">'.
                    '<title>#7.8</title>'.
                '</head>'.
                '<body>'.
                    '<table>'.
                        '<tr>'.
                            '<th>Місто</th>'.
                            '<th>Населення</th>'.
                            '<th>Координати</th>'.
                        '</tr>';

        $html_footer =
                    '</table>'.
                '</body>'.
            '</html>';

        fwrite( $filehandle_html, $html_header );

        foreach( $this->_cities as $city )
        {
            fwrite(
                $filehandle_html,
                '<tr>'.
                    '<td>' . $city['city'] . '</td>'.
                    '<td>' . $city['population'] . '</td>'.
                    '<td>' . $city['latitude'] . ', ' . $city['longitude'] . '</td>'.
                '</tr>'
            );
        }

        fwrite( $filehandle_html, $html_footer );

        fclose( $filehandle_html );

        // встановлюємо дозволи
        chmod( $this->_filepath_cities_html, 0644 );

        return $this;
    }

    /**
     * Перехід на нову адресу
     *
     * @return $this
     */
    protected function _redirectLocation() : self
    {
        header( 'Location: ' . $this->_filepath_cities_html );

        return $this;
    }

    /**
     * Головний метод
     */
    public function execute() : void
    {
        try
        {
            $this->_initialize();
            $this->_processUploadedFile();
            $this->_getCitiesFromCSVFile();
            $this->_sortCitiesByName();
            $this->_createCitiesHTMLFile();
            $this->_redirectLocation();

            // Альтернативний варіант запису з використанням "ланцюжків" (chains):

            //$this
            //    ->_initialize()
            //    ->_processUploadedFile()
            //    ->_getCitiesFromCSVFile()
            //    ->_sortCitiesByName()
            //    ->_createCitiesHTMLFile()
            //    ->_redirectLocation();
        }
        catch( Exception $e )
        {
            $this->_error_message = $e->getMessage();
        }
    }

    /**
     * Метод для отримання тексту помилки
     *
     * @return string
     */
    public function getError() : string
    {
        return $this->_error_message;
    }
}

$citiesParser = new CitiesParser(
    'userfile',                     // значення атрибута "name" для <input type="file">
    'Ukraine',                      // значення країни для парсингу
    './data/cities_ukraine.html'    // результуючий HTML-файл з містами
);
$citiesParser->execute();

?><!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <title>#7.10</title>
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

            $error_message = $citiesParser->getError();

            // відображаємо помилку, якщо вона встановлена в щось, окрім ""
            if( !empty( $error_message ) )
            {
                echo( '<li class="error">' . $error_message . '</li>' );
            }

            ?>
            <li>
                <input type="hidden" name="MAX_FILE_SIZE" value="<?= CitiesParser::MAX_FILE_SIZE ?>" />
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