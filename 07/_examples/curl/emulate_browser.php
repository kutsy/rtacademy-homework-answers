<?php

declare( strict_types=1 );

// cURL: Приклад емуляції браузера користувача

// починаємо (ініціалізуємо) сеанс
$ch = curl_init();

// ініціалізуємо параметри сеанса
curl_setopt( $ch, CURLOPT_URL, 'https://www.google.com/' );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );  // для повернення результату передачі в якості рядка
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );  // для слідування будь-якому заголовку "Location:"

$headers =  // для більш точної емуляції краще візьміть Request Headers вашого браузера з подібного запиту
[
    'Cache-Control: max-age=0', 'DNT: 1', 'Accept: text/html,application/xhtml+xml,application/xml',
    'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88',
    'Accept-Language: uk,ru;q=0.9,en-US;q=0.8,en;q=0.7',
];
curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt' );
curl_setopt( $ch, CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt' );

// виконуємо запит на сервер і отримуємо результат
$html = curl_exec( $ch );

// закінчуємо сеанс
curl_close( $ch );
