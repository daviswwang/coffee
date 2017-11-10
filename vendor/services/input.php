<?php

namespace services;

use coffee\exception\requestError;
use Whoops\Util\Misc;

class input
{

    public static function get($key = '' , $default = '' , $xss_filter = false)
    {
        return self::_deal_data($key,$default,$xss_filter,$_GET);
    }

    public static function post($key = '' , $default = '' , $xss_filter = false)
    {
        return self::_deal_data($key,$default,$xss_filter,$_POST);
    }

    public static function any($key = '' , $default = '' , $xss_filter = false)
    {
        return call_user_func_array("\services\input::".self::method(),[$key,$default,$xss_filter]);
    }

    public static function is_ajax()
    {
        if(Misc::isAjaxRequest()) return true;else return false;
    }

    public static function is_ajax_get()
    {
        if(self::is_ajax() && self::is_get()) return true;else return false;
    }

    public static function is_ajax_post()
    {
        if(self::is_ajax() && self::is_post()) return true;else return false;
    }

    public static function is_ajax_put()
    {
        if(self::is_ajax() && self::is_put()) return true;else return false;
    }

    public static function is_ajax_delete()
    {
        if(self::is_ajax() && self::is_delete()) return true;else return false;
    }

    public static function is_ajax_option()
    {
        if(self::is_ajax() && self::is_option()) return true;else return false;
    }

    public static function is_get()
    {
        if(strtolower(self::method()) == 'get') return true; else return false;
    }

    public static function is_post()
    {
        if(strtolower(self::method()) == 'post') return true; else return false;
    }

    public static function is_put()
    {
        if(strtolower(self::method()) == 'put') return true; else return false;
    }

    public static function is_delete()
    {
        if(strtolower(self::method()) == 'delete') return true; else return false;
    }

    public static function is_option()
    {
        if(strtolower(self::method()) == 'option') return true; else return false;
    }

    public static function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function ip($proxy = false)
    {
        if ($proxy) {
            $ip = empty($_SERVER["HTTP_X_FORWARDED_FOR"]) ? (empty($_SERVER["HTTP_CLIENT_IP"]) ? NULL : $_SERVER["HTTP_CLIENT_IP"]) : $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = empty($_SERVER["HTTP_CLIENT_IP"]) ? NULL : $_SERVER["HTTP_CLIENT_IP"];
        }

        if (empty($ip)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($p = strrpos($ip, ",")) {
            $ip = substr($ip, $p+1);
        }

        return trim($ip);
    }


    // @param bool   $restrict  是否进行严格的查检, 此方式为用正则对host进行匹配
    // @param string $allow       允许哪些 referrer 过来请求
    // @return true / false       在允许的列表内返回true

    public function referrer($restrict = true, $allow = '')
    {
        $referrer = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : null;
        if (empty($referrer)) { return true;    } /* 空的 referer 直接允许 */

        if ($restrict) {
            $url = parse_url($referrer);
            if (empty($url['host'])) { return false; }
            $allow = '/'.str_replace('.', '\.', $allow).'/';
            return 0 < preg_match($allow, $url['host']);
        }

        return false !== strpos($referrer, $allow);
    }

    public static function xss_filter($data)
    {
        $xss_path = C_VENDOR."component".DIRECTORY_SEPARATOR.'xss'.DIRECTORY_SEPARATOR.'HTMLPurifier.auto.php';

        if(!file_exists($xss_path))
            throw new requestError("please install xss component.");

        if(!isset(di()['xss_filter']))
        {
            require_once $xss_path;
            di()->setXss_filter(new \HTMLPurifier(config::get('app.xss')));
        }

        if(is_array($data))
            return di('xss_filter')->purifyArray($data);
        
        return di('xss_filter')->purify($data);
    }

    private static function _deal_data($key , $default , $xss_filter , $data)
    {
        if(empty($key) && empty($default))
        {
            if($xss_filter)
                return self::xss_filter($data);
            return $data;
        }

        if($key && isset($data[$key]))
        {
            if($xss_filter)
                return self::xss_filter($data[$key]);
            return $data[$key];
        }
        
        return $default;
    }
}