<?php

namespace netvod\exceptions;

use Exception;

class ActivateException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}