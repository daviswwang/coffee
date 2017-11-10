<?php

namespace test\app;

use services\config;

class _empty
{
    public function index()
    {
        raise('file is not found.',404);
    }
}