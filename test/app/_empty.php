<?php

namespace test\app;

use services\config;

class _empty
{
    public function index()
    {
        raise(config::get('default.404.note'),404);
    }
}