<?php

declare( strict_types=1 );

spl_autoload_register(
    function( $class )
    {
        require_once( __DIR__ . '/' . str_replace( '\\', '/', $class ) . '.php' );
    }
);

$postsModels = new \lib\models\PostsModel();
$posts       = $postsModels->getList();

require_once( './includes/header.php' );

?>
<main class="main-posts">
    <div class="top-article">
        TOP ARTICLE
        <a href="#">Nintendo of America President Responds to Reports of New Switch Hardware</a>
    </div>

    <div class="posts">
        <?php

        if( !empty( $posts ) )
        {
            foreach( $posts as $post )
            {
                /** @var \lib\entities\Post $post */
                ?>
                <article>
                    <a href="<?= $post->getUrl() ?>" class="cover"><?= $post->getCover()->getImgTag( $post->getCover()->getListImgAttributes() ) ?></a>
                    <a href="<?= $post->getUrl() ?>" class="title"><h2><?= $post->getTitle() ?></h2></a>
                    <time datetime="<?= $post->getPublishDate('c') ?>"><?= $post->getPublishDate() ?></time>
                    <a href="<?= $post->getUrl() ?>" class="description"><?= $post->getDescription() ?></a>
                    <a href="<?= $post->getUrl() ?>" class="more">Continue Reading</a>
                </article>
                <?php
            }

            // TODO: <div class="article-empty"></div> коли не кратне 3 в останньому ряді
        }
        else
        {
            // коли відсутні записи/пости
            ?>
            <div class="no-articles">No articles</div>
            <?php
        }

        ?>
    </div>
</main>
<?php

require_once( './includes/footer.php' );
