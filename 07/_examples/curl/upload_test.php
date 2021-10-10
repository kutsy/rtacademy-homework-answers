<?php

declare( strict_types=1 );

// cURL: Приклад надсилання файла

// починаємо (ініціалізуємо) сеанс
$ch = curl_init();

curl_setopt( $ch, CURLOPT_URL, 'http://127.0.0.1/curl/upload.php' ); // завантажте і виконайте локально
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

// встановлюємо метод запиту як POST
curl_setopt( $ch, CURLOPT_POST, true );

// створюємо обʼєкт CURLFile з адресою файла (в нашому випадку це зображення) та його MIME-типом,
// бо інакше файл на сервер за допомогою cURL не надіслати
// додайте файл "penguin.jpg" (або будь-який інший) для тесту
$curl_file = curl_file_create( './penguin.jpg', 'image/jpeg' );

// встановлюємо цей обʼєкт в параметри запиту
curl_setopt( $ch, CURLOPT_POSTFIELDS, [ 'testfile' => $curl_file ] );

// виконуємо запит на сервер і отримуємо результат
$html = curl_exec( $ch );

// закінчуємо сеанс
curl_close( $ch );
