<?php

namespace test\app\v1;

use services\db;
use services\input;

class test
{
    public function index()
    {
        input::any();
        return db::connect()->table('user')->where('id',1)->fetchRow();
//
//        print_r($a);
//        raise('this page is test/index.');
    }
}