<?php

namespace services;

class request
{
    public static function get_header($key = '')
    {
        $key = strtoupper($key);
        if(isset($_SERVER['HTTP_'.$key]))
            return $_SERVER['HTTP_'.$key];
        else
            return false;
    }

    public static function get_server($key = '')
    {
        $key = strtoupper($key);
        if(isset($_SERVER[$key]))
            return $_SERVER[$key];
        else
            return false;
    }

    public static function get_class()
    {
        return di('request')->get_class();
    }

    public static function get_action()
    {
        return di('request')->get_action();
    }

    public static function get_namespace()
    {
        return di('request')->get_namespace();
    }
}