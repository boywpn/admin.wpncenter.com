<?php

namespace App\Http\Controllers\Games\Sa;

class DES
{
    public $key;
    public $iv;

    public function __construct( $key, $iv=0 ) {
        $this->key = $key;
        if( $iv == 0 ) {
            $this->iv = $key;
        } else {
            $this->iv = $iv;
        }
    }

    public function encrypt($str) {
        return base64_encode( openssl_encrypt($str, 'DES-CBC', $this->key, OPENSSL_RAW_DATA, $this->iv ) );
    }
}
