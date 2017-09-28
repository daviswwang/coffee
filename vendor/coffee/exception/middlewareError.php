<?php

namespace coffee\exception;

use services\interpret;
use Throwable , \Exception;

class middlewareError extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(interpret::language($message), 600 + $code, $previous);
    }
}