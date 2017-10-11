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
//        self::$instance->respond('/[:controller]?/[:action]?',function($request){
//            print_r($request->paramsNamed());
//            return true;
//        });
//

        echo password_hash('root#pass@rp',PASSWORD_BCRYPT);exit;
        self::$instance->respond('*',function($rq){
            print_r("\\services\\route::".strtolower($rq->method()));
//            return call_user_func_array(,[$rq->pathname]);
        });


//
        try
        {
            self::$instance->dispatch();
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

        //中间件操作
        middleware::callback(middleware::BGR);

    }

    public static function get($uri = '' , $params = NULL)
    {
        print_r($uri);
//        return;
//        $a = self::$instance->respond('GET',$uri,function()use($params){
//            print_r($params);
//            throw new \Exception('aaaaa');
//        });
    }

    public static function post($uri = '' , $params = [])
    {

    }

    public static function any($uri = '' , $params = [])
    {

    }
}