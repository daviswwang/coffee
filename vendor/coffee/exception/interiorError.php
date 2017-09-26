<?php

namespace coffee\exception;

use \Exception , Throwable , services\interpret;

class interiorError extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(interpret::language($message), $code, $previous);
    }
}