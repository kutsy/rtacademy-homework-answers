<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>#7.3</title>
</head>
<body>
<?php

$str = 'Hello World';

$start = hrtime(true);
var_dump( myStrShuffle( $str ) );  // "owldrHlelo "
$end = hrtime(true);

echo( ( ( $end-$start ) / 1000000 ) . 'ms <br>' );

$start = hrtime(true);
var_dump( str_shuffle( $str ) );  // "owldrHlelo "
$end = hrtime(true);

echo( ( ( $end-$start ) / 1000000 ) . 'ms <br>' );

// ваш код функції myStrShuffle пишіть нижче

function myStrShuffle( string $string ) : string
{
    $string_arr = str_split( $string );
    shuffle( $string_arr );
    return implode( $string_arr );
}

?>
</body>
</html>
