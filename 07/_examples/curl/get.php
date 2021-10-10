<?php

declare( strict_types=1 );

// cURL: Приклад GET запита

// починаємо (ініціалізуємо) сеанс
$ch = curl_init();

// ініціалізуємо параметри сеанса:
// встановлюємо адресу сайта для підключення
curl_setopt( $ch, CURLOPT_URL, 'https://www.google.com/' );

// встановлюємо опцію повернення у вигляді рядка
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

// виконуємо запит на сервер і отримуємо результат
// (в цьому випадку це HTML-сторінка) у змінну $html
$html = curl_exec( $ch );

// у випадку вининення помилки - відобраємо її
if( curl_error( $ch ) )
{
    echo( 'Помилка: ' . curl_error( $ch ) );
}

// закінчуємо сеанс
curl_close( $ch );
