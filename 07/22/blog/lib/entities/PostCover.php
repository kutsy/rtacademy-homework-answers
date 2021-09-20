<?php

declare( strict_types=1 );

namespace lib\entities;

class PostCover
{
    protected int    $_id;
    protected string $_filename;
    protected string $_alt;

    public function getId(): int
    {
        return $this->_id;
    }

    public function setId( int $id ): void
    {
        $this->_id = $id;
    }

    public function getFilename(): string
    {
        return $this->_filename;
    }

    public function setFilename( string $filename ): void
    {
        $this->_filename = $filename;
    }

    public function getAlt(): string
    {
        return $this->_alt;
    }

    public function setAlt( string $alt ): void
    {
        $this->_alt = $alt;
    }
}