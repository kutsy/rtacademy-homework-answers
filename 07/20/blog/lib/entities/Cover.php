<?php

declare( strict_types=1 );

namespace lib\entities;

class Cover
{
    protected string $_filename;
    protected string $_alt;

    public function __construct( string $filename, string $alt = '' )
    {
        $this->_filename = $filename;
        $this->_alt      = $alt;
    }

    public function getListImgAttributes(): array
    {
        return
        [
            'src'    => './images/' . $this->_filename . '_310.jpg',
            'srcset' => './images/' . $this->_filename . '_310.jpg 310w, ./images/' . $this->_filename . '_350.jpg 350w, ./images/' . $this->_filename . '_550.jpg 550w, ./images/' . $this->_filename . '_640.jpg 640w',
            'sizes'  => '(max-width: 48rem) 550px, (max-width: 62rem) 350px, (max-width: 75rem) 310px, 550px',
            'alt'    => htmlspecialchars( $this->_alt ),
        ];
    }

    public function getSingleImgAttributes(): array
    {
        return
        [
            'src'   => './images/' . $this->_filename . '_1200.jpg',
            'alt'    => htmlspecialchars( $this->_alt ),
        ];
    }

    public function getImgTag( array $attrs ): string
    {
        $img_tag = '<img ';

        foreach( $attrs as $key => $value )
        {
            $img_tag .= $key . '="' . $value . '" ';
        }

        $img_tag .= '/>';

        return $img_tag;
    }
}