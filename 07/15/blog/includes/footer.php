<footer>

    <div class="inner">

        <div class="main">
            <h3><a href="#">Gaming News</a></h3>

            <ul class="social">
                <li><a href="#"><img src="./images/icons/001-facebook.svg" alt="facebook"></a></li>
                <li><a href="#"><img src="./images/icons/013-twitter.svg" alt="twitter"></a></li>
                <li><a href="#"><img src="./images/icons/008-youtube.svg" alt="youtube"></a></li>
            </ul>

            <p>Copyright, 2021</p>
        </div>

        <div class="nav">
            <h3>Main Navigation</h3>

            <ul>
                <li><a href="/blog">Home</a></li>
                <?php

                if( !empty( $websiteMenuItems ) )
                {
                    foreach( $websiteMenuItems as $item )
                    {
                        echo( '<li><a href="' . $item->getHref() . '">' . $item->getTitle() . '</a></li>' );
                    }
                }

                ?>
            </ul>
        </div>

    </div>

</footer>

</body>
</html>