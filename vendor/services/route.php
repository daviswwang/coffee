<?php

namespace services;

class route
{
    private static $instance = NULL;
    
    public static function listen()
    {
        if(!self::$instance)
            self::$instance = new \Klein\Klein();

        config::get('','route');

//        self::$instance->respond('*',function($rq , $rs , $s){
//            print_r($rq);
//            print_r($rs);
//            print_r($s);
//        });

        try
        {
            self::$instance->dispatch();
        }
        catch (\Exception $e)
        {
            throw new \Exception('eeeee');
        }

    }

    public static function get($uri = '' , $params = NULL)
    {
        $a = self::$instance->respond('GET',$uri,function()use($params){
            print_r($params);
            throw new \Exception('aaaaa');
        });
    }

    public static function post($uri = '' , $params = [])
    {

    }

    public static function any($uri = '' , $params = [])
    {

    }
}