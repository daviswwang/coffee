<?php

namespace test\app\v1;

use services\db;

class test
{
    public function index()
    {
        return db::connect()->table('user')->where('id',1)->fetchRow();
//
//        print_r($a);
//        raise('this page is test/index.');
    }
}