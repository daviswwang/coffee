<?php

namespace coffee\exception;

use \Exception , Throwable , services\interpret;

class mysqlError extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(interpret::language($message) , 100 + $code, $previous);
    }
}