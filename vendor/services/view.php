<?php

namespace services;

use coffee\exception\viewError;

class view
{

    private static $view = NULL;


    /* @param $key string || array
     * @param $val string
     * @return null
     * */
    public static function assign($key = NULL, $val = '')
    {
        self::instance()->assign(is_array($key) ? $key : [$key=>$val]);

        return self::instance();
    }

    /*
     * @param $file string
     * @param $assign array
     * @return null
     * */
    public static function display($file = '' , array $assign = [])
    {
        return self::assign($assign)->display($file);
    }


    /*
     * @param $file string
     * @param $assign array
     * @return string
     * */
    public static function render($file = '' , array $assign = [])
    {
        return self::assign($assign)->display($file , true);
    }

    private static function instance()
    {
        if(!class_exists("\\component\\template\\view"))
        {
            throw new viewError('please install view component.');
        }

        if(self::$view === NULL)
        {
            self::$view = new \component\template\view();
        }

        return self::$view;
    }
}