<?php

namespace services;

class route
{
    private static $instance = NULL;

    private static $config   = [];
    
    public static function listen()
    {
        global $auto;

        //中间件操作 得到请求之后操作
        middleware::callback(middleware::BGR);

        //收到请求 -- 注册应用
        di('request')->register($auto);

        if(!self::$config)
            self::$config = config::get('','route');

        //触发NotFound
        if(self::match_config_route() === false && self::match_path_info() === false)
        {
            middleware::callback(middleware::BNF);
            self::trigger_not_found();
            middleware::callback(middleware::ANF);
        }
    }

    private static function match_config_route()
    {
        if(isset(self::$config[parse::get_request_path_info()]))
        {
            $params = self::match_path_info(self::$config[parse::get_request_path_info()]);
        }
    }

    private static function match_path_info($path_info = '')
    {
        if(!$path_info) $path_info = parse::get_request_path_info();

        print_r($path_info);
    }

    private static function trigger_not_found()
    {

    }
}