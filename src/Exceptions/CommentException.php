<?php

namespace netvod\Exceptions;

class CommentException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}