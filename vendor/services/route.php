<?php

namespace services;

class route
{
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

        self::match_route();
    }

    private static function match_route()
    {
        if(isset(self::$config[parse::get_request_path_info()]))
        {
            $params = self::match_path_info(self::$config[parse::get_request_path_info()]);
        }
        else
            $params = self::match_path_info();

        middleware::callback(middleware::AGR);

        di('reflection')->object(di('request'))->setPrivateAttribute('service',$params);
    }

    private static function match_path_info($path_info = '')
    {
        if(!$path_info) $path_info = parse::get_request_path_info();

        $namespace = $old_namespace = "\\".(config::get('app_name'))."\\".(config::get('app_directory'))."\\";
        $status = 200;

        if(empty($path_info) || $path_info == '/')
        {
            $class  = config::get('default.class');
            $action = config::get('default.action');
            $namespace .= $class."\\";
        }
        else
        {
            $path   = explode('/',trim($path_info,'/'));

            if(count($path) == 1)
            {
                $class  = $path[0];
                $action = config::get('default.action');
                $namespace .= $class."\\";
            }
            else
            {
                $class = $action = '';

                $file_path = C_ITEM.config::get('app_directory').DIRECTORY_SEPARATOR;

                foreach ($path as $k=>$v)
                {
                    if(is_dir($file_path.$v))
                    {
                        $namespace .= $v."\\";
                        if(($k+1) == count($path))
                        {
                            $class  = config::get('default.class');
                            $action = config::get('default.action');
                        }

                        $file_path .= $v.DIRECTORY_SEPARATOR;
                    }
                    elseif(file_exists($file_path.$v.C_EXT))
                    {
                        $class = $v;
                        if(($k+1) == count($path))
                        {
                            $action = config::get('default.action');
                        }
                    }
                    elseif(($k+1) == count($path) && !empty($class))
                    {
                        $action = $v;
                    }
                }
            }
        }

        //组装
        if(empty($class) && empty($action))
        {
            $class = config::get('default.404.class');
            $action= config::get('default.404.action');
            middleware::callback(middleware::BNF);
            $status = 404;
            $namespace = $old_namespace;
        }

        return [
            'namespace'=>$namespace,
            'application'=>$namespace.$class,
            'class'=>$class,
            'action'=>$action,
            'status'=>$status
        ];
    }


}