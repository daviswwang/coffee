<?php
// +----------------------------------------------------------------------
// | NaRest [ Restful微型框架 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.naofbear.com All rights reserved.
// +----------------------------------------------------------------------
// | The author has all the explanatory power of this framework
// +----------------------------------------------------------------------
// | Author: naofbear wechat: naofbear email: huakaiquan@qq.com
// +----------------------------------------------------------------------

namespace services;

class cookie
{
    public static function set($key = '', $val = '' , $exp = 86400 , $path = '/' , $domain = '' )
    {
        return setcookie($key , json_encode($val) , time() + $exp , $path , $domain);
    }

    public static function get($key = '')
    {
        if(!isset($_COOKIE[$key]))
            return false;

        return json_decode($_COOKIE[$key],true);
    }

    public static function del($key = '')
    {
        return self::set($key , '' , -86400);
    }
}