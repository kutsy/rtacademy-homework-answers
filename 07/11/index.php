<?php

declare( strict_types=1 );

spl_autoload_register(
    function( $class )
    {
        require_once( __DIR__ . '/lib/' . $class . '.php' );
    }
);

// Author
$author1 = new Author();
$author1->setId( 1 );
$author1->setFirstName( 'Adam' );
$author1->setLastName( 'Bankhurst' );

$author2 = new Author();
$author2->setId( 3 );
$author2->setFirstName( 'Joe' );
$author2->setLastName( 'Skrebels' );

$author3 = new Author();
$author3->setId( 6 );
$author3->setFirstName( 'Kat' );
$author3->setLastName( 'Bailey' );

// Category
$category1 = new Category();
$category1->setId( 1 );
$category1->setTitle( 'PC' );
$category1->setAlias( 'pc' );

$category2 = new Category();
$category2->setId( 2 );
$category2->setTitle( 'Playstation' );
$category2->setAlias( 'playstation' );

$category3 = new Category();
$category3->setId( 3 );
$category3->setTitle( 'Nintendo' );
$category3->setAlias( 'nintendo' );

// Post + Cover 1
$cover1 = new Cover( '01', 'Bayonetta' );

$post1 = new Post();
$post1->setId( 1 );
$post1->setTitle( 'Bayonetta 3 Is \'Progressing Well\' Despite Its No Show at E3 2021, Nintendo Says' );
$post1->setAlias( 'bayonetta-3-is-progressing-well-despite-its-no-show-at-e3-2021-nintendo-says' );
$post1->setDescription( 'Bayonetta 3 was first announced at The Game Awards 2017.' );
$post1->setAuthor( $author1 );
$post1->setPublishDate( '2021-06-20 11:11:00' );
$post1->setCategory( $category1 );
$post1->setCover( $cover1 );

var_dump( $post1 );

// Post + Cover 2
$cover2 = new Cover( '02', 'Sea of Thieves' );

$post2 = new Post();
$post2->setId( 2 );
$post2->setTitle( 'A Pirate\'s Life is a love letter to the Pirates of the Caribbean films and the Disneyland attraction.' );
$post2->setAlias( 'a-pirates-life-is-a-love-letter-to-the-pirates-of-the-caribbean-films-and-the-disneyland-attraction' );
$post2->setDescription( 'A Pirate\'s Life is a love letter to the Pirates of the Caribbean films and the Disneyland attraction.' );
$post2->setAuthor( $author2 );
$post2->setPublishDate( '2021-06-20 10:51:00' );
$post2->setCategory( $category2 );
$post2->setCover( $cover2 );

var_dump( $post2 );

// Post + Cover 3
$cover3 = new Cover( '03', 'Sony' );

$post3 = new Post();
$post3->setId( 3 );
$post3->setTitle( 'Sony Wants to Support Cross-Play More, PlayStation Boss Jim Ryan Says' );
$post3->setAlias( 'sony-wants-to-support-cross-play-more-playstation-boss-jim-ryan-says' );
$post3->setDescription( '"We support and encourage cross-play."' );
$post3->setAuthor( $author3 );
$post3->setPublishDate( '2021-06-19 17:50:00' );
$post3->setCategory( $category3 );
$post3->setCover( $cover3 );

var_dump( $post3 );