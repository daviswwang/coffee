<?php

namespace coffee\exception;

use \Exception , Throwable;

class cacheError extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, 500 + $code, $previous);
    }
}