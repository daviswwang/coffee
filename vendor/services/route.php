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

        print_r(parse::get_request_path_info());
//
//        if(!self::$instance)
//            self::$instance = new \Klein\Klein();

        if(!self::$config)
            self::$config = config::get('','route');

//        self::parse_request_method();
    }

    private static function parse_request_method()
    {
        if(self::$config)
        {
            $method = ['GET','POST'];
            $params = [];

            foreach (self::$config as $k=>$v)
            {
                if(is_array($v))
                {
                    if(isset($v['method'])) $method = $v['method'];
                    $params = $v;
                }
                elseif(is_string($v))
                {
                    if(strstr($v,'#'))
                    {
                        $t = explode('#',$v);
                        $method = $t[0];
                        $params = $t[1];
                    }
                }

                self::$instance->respond($method,$k,function($request,$response,$service,$app)use($params){
                    di('reflection')->object(di('request'))->setPrivateAttribute('isHasRoute',true);
                    route::parse_request_route($request,$response,$service,$app,$params);
                });

                var_dump(self::$instance->routes->cloneEmpty()->isEmpty());
            }
        }
//        else
//        {
//            if(di('request')->isHasRoute() === false)
//            {
//                self::$instance->respond(['GET','POST'],'*',function($request,$response,$service,$app){
//                    print_r(1111);
//                });
//            }
//
//            self::$instance->dispatch();
//        }



    }

    public static function parse_request_route($request , $response , $service , $app , $params)
    {

    }
}