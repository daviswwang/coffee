<?php

namespace test\app\v1;

use \services\db;

class test
{
    public function index()
    {
        raise('error',1000);
        return db::connect()->user('id',[1,2,3])->fetchAll('id');

//        foreach ($a as $v){print_r($v['user']);}
        print_r($a);
    }
}