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
            return $_SERVER['PATH_INFO'];
        }
        elseif(isset($_SERVER['PHP_SELF']) && !empty($_SERVER['PHP_SELF']))
        {
            return $_SERVER['PHP_SELF'];
        }
        else
        {
            return rtrim(strstr($_SERVER['REQUEST_URI'],'?',true),'?');
        }
    }
}