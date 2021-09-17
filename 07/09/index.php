<?php

declare( strict_types=1 );

//
// УВАГА! Скрипт працює тільки для PHP 8.0 і новіше
//

define( 'MAX_FILE_SIZE', 10485760 );            // константа максимального розміру файла
define( 'IMAGE_MIN_DIMENSIONS', 500 );          // константа для мінімальної ширини/висоти оригінального зображення
define( 'IMAGE_HEIGHT', 300 );                  // константа для максимальної висоти зменшеного зображення

$error_message  = '';                           // змінна з текстом помилки, якщо виникне
$new_image_path = '';                           // шлях до зображення, якщо все пройде успішно

/**
 * Перевірка коректності завантаження файла зображення на сервер
 *
 * @param string $name
 * @param int    $max_size
 *
 * @return bool
 */
function processUploadedImage( string $name, int $max_size = MAX_FILE_SIZE ): bool
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
    $valid_mimetypes = [ 'image/jpeg', 'image/jpeg', 'image/png', 'image/gif' ];

    if( !in_array( $_FILES[ $name ]['type'], $valid_mimetypes ) )
    {
        $error_message = 'Помилка. Файл повинен мати формат JPEG, PNG або GIF.';
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
 * Вирізання зображення в пропорціях 4:5
 *
 * @param string $file_path Шлях до зображення на сервері
 *
 * @return \GdImage|null
 */
function cropImage( string $file_path ) : ?\GdImage
{
    // для звернення до змінної $error_message (і встановлення інших її значень) за межами функції необхідно використовувати "global"
    global $error_message;

    $file_contents = file_get_contents( $file_path );

    if( empty( $file_contents ) )
    {
        $error_message = "Помилка: файл $file_path не існує";
        return null;
    }

    // створення екземпляру класу GdImage з зображення, автоматично визначаючи його тип
    $image_source = imagecreatefromstring( $file_contents );

    $image_width  = imagesx( $image_source );           // визначаємо ширину зображення в пікселях
    $image_height = imagesy( $image_source );           // визначаємо висоту зображення в пікселях

    // перевірка мінімальної ширини та висоти
    if( $image_width < IMAGE_MIN_DIMENSIONS || $image_height < IMAGE_MIN_DIMENSIONS )
    {
        $error_message = 'Помилка. Розмір зображення має бути більшим за ' . IMAGE_MIN_DIMENSIONS . ' px.';
        return null;
    }

    // обчислюємо розміри для пропорції 4:5
    if( $image_width < $image_height )
    {
        $side_4x = $image_width;
        $side_5x = (int)( 5 * $image_width / 4 );
    }
    else        // $image_width >= $image_height
    {
        $side_4x = (int)( 4 * $image_height / 5 );
        $side_5x = $image_height;
    }

    // вирізаємо зображення в форматі 4:5
    $image_result = imagecrop(
        $image_source,
        [
            'x'              => (int)( $image_width / 2 - $side_4x / 2 ),
            'y'              => (int)( $image_height / 2 - $side_5x / 2 ),
            'width'          => $side_4x,
            'height'         => $side_5x,
        ]
    );

    if( ! $image_result )
    {
        $error_message = "Виникла помилка при вирізанні частини зображення";
        return null;
    }

    imagedestroy( $image_source );                    // звільняємо пам'ять, зайняту початковим зображенням

    return $image_result;
}

/**
 * Зменшення зображення до $image_height по висоті
 *
 * @param \GdImage $image_source
 * @param int $image_height
 *
 * @return \GdImage|null
 */
function resizeImage( \GdImage $image_source, int $image_height = IMAGE_HEIGHT ) : ?\GdImage
{
    // для звернення до змінної $error_message (і встановлення інших її значень) за межами функції необхідно використовувати "global"
    global $error_message;

    // зменшимо зображення до 300px по висоті; ширина буде задана автоматично з пропорцій
    $image_result = imagescale( $image_source, $image_height );

    if( ! $image_result )
    {
        $error_message = "Виникла помилка при зменшенні частини зображення";
        return null;
    }

    return $image_result;
}

/**
 * Збереження зображення у форматі JPEG з унікальним імʼям
 *
 * @param \GdImage $image_source
 *
 * @return bool
 */
function saveImage( \GdImage $image_source ) : bool
{
    // для звернення до змінної $error_message (і встановлення інших її значень) за межами функції необхідно використовувати "global"
    global $error_message;

    // для звернення до змінної $new_image_path (і встановлення інших її значень) за межами функції необхідно використовувати "global"
    global $new_image_path;

    $new_image_path = './data/' . microtime( true ) . '.jpg';

    // зберігаємо з новим ім'ям у форматі JPEG
    if( ! imagejpeg( $image_source, $new_image_path ) )
    {
        $error_message  = "Виникла помилка при збереженні нового зображення $new_image_path";
        $new_image_path = '';       // обнуляємо цю змінну, бо нижче спрацює створення елементу <img>
        return false;
    }

    imagedestroy( $image_source );                      // звільняємо пам'ять, зайняту зменшеним зображенням

    return true;
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

    $input_name = 'image';                   // значення атрибута "name" для <input type="file">

    // перевірка на коректність надсилання файлу зображення
    if( ! processUploadedImage( $input_name ) )
    {
        // виникла помилка
        return;
    }

    $image = cropImage( $_FILES[ $input_name ]['tmp_name'] ?? '' );

    if( !$image )
    {
        // виникла помилка
        return;
    }

    $image = resizeImage( $image );

    if( !$image )
    {
        // виникла помилка
        return;
    }

    $image = saveImage( $image );

    if( !$image )
    {
        // виникла помилка
        return;
    }
}

// Запуск головної функції
main();

?><!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <title>#7.9</title>
    <style>
        body {
            font: normal 1rem/1.5rem Verdana,sans-serif;
            color: #000;
            margin: 0;
            padding: 0;
        }
        main form {
            width: 40rem;
            margin: 10vh auto 0 auto;
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

        #image {
            padding: 2rem 0;
            margin: 2rem auto;
            background-color: #fafafa;
        }
            #image img {
                display: block;
                margin: 0 auto;
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
                <label for="city">Файл з зображенням у форматі JPEG, PNG або GIF:</label>
                <input name="image" type="file" />
            </li>
            <li>
                <button type="submit">Надіслати</button>
            </li>
        </ul>
    </form>
    <?php

    // відображаємо зображення
    if( !empty( $new_image_path ) )
    {
        echo( '<div id="image"><img src="'.$new_image_path.'" height="300" alt=""></div>' );
    }

    ?>
</main>

</body>
</html>