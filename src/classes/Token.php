<?php

namespace netvod\classes;

class Token
{

    public static function generateToken(): string
    {
        $token = openssl_random_pseudo_bytes(8);
        return bin2hex($token);
    }
}