<?php

declare( strict_types=1 );

// cURL: Приклад роботи з Cookie

// починаємо (ініціалізуємо) сеанс
$ch = curl_init();

// ініціалізуємо параметри сеанса
curl_setopt( $ch, CURLOPT_URL, 'https://www.google.com/' );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

// для того, щоб cURL зберіг кукі у файл достатньо вказати шлях до такого файла
// в параметрах CURLOPT_COOKIEFILE та CURLOPT_COOKIEJAR
curl_setopt( $ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt' );
curl_setopt( $ch, CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt' );

// виконуємо запит на сервер і отримуємо результат
$html = curl_exec( $ch );

// закінчуємо сеанс
curl_close( $ch );
