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

        //中间件操作
        middleware::callback(middleware::BGR);
    }

    public static function get($uri = '' , $params = NULL)
    {
        self::$instance->respond('GET',$uri,function($request,$response,$service,$app)use($params){
            route::parse_request_route($request,$response,$service,$app,$params);
        });
    }

    public static function post($uri = '' , $params = [])
    {
        self::$instance->respond('POST',$uri,function($request,$response,$service,$app)use($params){
            route::parse_request_route($request,$response,$service,$app,$params);
        });
    }

    public static function any($uri = '' , $params = [])
    {
        self::$instance->respond(['GET','POST'],$uri,function($request,$response,$service,$app)use($params){
            route::parse_request_route($request,$response,$service,$app,$params);
        });
    }

    public static function parse_request_route($request , $response , $service , $app , $params)
    {
        print_r($request);
    }
}