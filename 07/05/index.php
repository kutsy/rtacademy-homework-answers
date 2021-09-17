<?php

declare( strict_types=1 );

$city_raw = $_GET['city'] ?? '';

function cityCapitalize( string $city ): string
{
    $city = preg_replace( '#[^\sa-z-]#i', '', $city );

    return
        ucfirst(
            strtolower(
                trim( $city )
            )
        );
}

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <title>#7.5</title>
    <style>
        #city {
            width : 15rem;
        }
        .city {
            padding    : 1rem;
            margin     : 2rem 0;
            background : #f0f0f0;
        }
    </style>
</head>
<body>
    <form action="" method="GET">
        <label for="city">Місто:</label>
        <input type="text" id="city" name="city" value="<?= htmlspecialchars( $city_raw ) ?>" placeholder="Введіть місто (тільки латиниця)">
        <button type="submit">Надіслати</button>
    </form>
    <?php

    $city = cityCapitalize( $city_raw );

    if( !empty($city) )
    {

    ?>
    <div class="city"><?= $city ?></div>
    <?php

    }

    ?>
</body>
</html>
