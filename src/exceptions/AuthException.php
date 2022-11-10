<?php

namespace netvod\exceptions;

use Exception;

class AuthException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}