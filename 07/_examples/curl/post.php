<?php

declare( strict_types=1 );

// Приклад POST запита

// починаємо (ініціалізуємо) сеанс
$ch = curl_init();

curl_setopt( $ch, CURLOPT_URL, 'https://www.facebook.com/' );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
// встановлюємо метод запиту як POST
curl_setopt( $ch, CURLOPT_POST, true );

// масив з даними форми (ключ - "name" елемента форми, значення - "value" елемента форми)
$array =
[
    'email' => 'Mark.Zuckerberg@facebook.com',
    'pass'  => 'SomeCoolPassword',
];

// встановлюємо цей масив з даними форми в параметри запиту
curl_setopt( $ch, CURLOPT_POSTFIELDS, $array );

// виконуємо запит на сервер і отримуємо результат
$html = curl_exec( $ch );

// закінчуємо сеанс
curl_close( $ch );
