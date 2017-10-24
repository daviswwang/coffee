<?php

namespace services;

class parse
{
    public static function get_class_name_by_namespace($str = '')
    {
        if(strstr($str,'\\'))
            return basename(str_replace('\\','/',$str));

        return basename($str);
    }

    public static function get_request_path_info()
    {
        if(isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO']))
        {
            $path_info = $_SERVER['PATH_INFO'];
        }
        elseif(isset($_SERVER['PHP_SELF']) && !empty($_SERVER['PHP_SELF']))
        {
            $path_info = $_SERVER['PHP_SELF'];
        }
        else
        {
            $path_info = rtrim(strstr($_SERVER['REQUEST_URI'],'?',true),'?');
        }

        if(empty($path_info))
            $path_info = '/';

        return $path_info;
    }

    public static function get_config_by_dot($key = '' , $config = [])
    {
        if(strstr($key,'.'))
        {
            foreach (explode('.',$key) as $v)
            {
                if(!isset($config[$v])) continue;

                $config = self::get_config_by_dot($v,$config);
            }
        }
        elseif(isset($config[$key])) $config = $config[$key];
        else $config = [];

        return $config;
    }
}