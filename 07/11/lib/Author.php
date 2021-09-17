<?php

declare( strict_types=1 );

class Author extends User
{
    public function getUrl(): string
    {
        return './author.php?id=' . $this->_id;
    }
}