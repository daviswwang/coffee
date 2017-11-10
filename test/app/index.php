<?php

namespace test\app;

use services\input;

class index
{
    public function index()
    {
        print_r(input::get('a','',true));
        raise('welcome to coffee restful api framework.');
    }
}