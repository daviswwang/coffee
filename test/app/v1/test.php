<?php

namespace test\app\v1;

use \Illuminate\Support\Facades\DB as db;

class test
{
    public function index()
    {
        $a =  db::select('select * from chain_user where id = :id', [':id'=>1]);

        print_r($a);
        echo 1;
    }
}