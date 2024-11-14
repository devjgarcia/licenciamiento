<?php

namespace App\Seguridad;

class GeneraPassword
{
    private $str      = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890*/#@.+-';
    
    public function getStr()
    {
        return $this->str;
    }

    public function generatePassword( $length = 8 )
    {
        $pass = '';
        for( $i = 0; $i < $length; $i++ ) {
            $pass .= substr( $this->getStr(), rand(0,62), 1 );
        }

        return $pass;
    }
}