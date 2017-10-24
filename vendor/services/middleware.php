<?php

namespace services;

use coffee\exception\middlewareError;

class middleware
{
    const BGR = 'BEFORE_GET_ROUTE';

    const AGR = 'AFTER_GET_ROUTE';

    const BEA = 'BEFORE_EXEC_APP';

    const AEA = 'AFTER_EXEC_APP';

    const BNF = 'BEFORE_NOT_FOUND';


    private static $services = [

        'BEFORE_GET_ROUTE'  =>[],

        'AFTER_GET_ROUTE'   =>[],

        'BEFORE_EXEC_APP'   =>[],

        'AFTER_EXEC_APP'    =>[],

        'BEFORE_NOT_FOUND'  =>[]
        
    ];

    private static $enable = false;

    public static function listen()
    {
        //载入核心中间件
        self::loadCoreMiddleware();

        //判断是否启用中间件
        if(config::get('middleware') === true)
        {
            self::$services = array_merge_recursive(self::$services,config::get('middleware_config'));
            self::$enable   = true;
        }

        return true;
    }

    public static function callback($type = self::BGR)
    {
        if(!self::$services[$type] || self::$enable === false) return false;

        array_walk(self::$services[$type],function($v,$k)
        {
            if(!class_exists($k)) throw new middlewareError("class $k is not exists.");

            if(!isset($v['exec_func']) || empty($v['exec_func']))
                throw new middlewareError("please set the default execution method at key : {$v['exec_func']}");

            if(!isset($v['conf_info']) || empty($v['conf_info']))
                $object = new $k();
            else
            {
                $conf = is_array($v['conf_info']) ? $v['conf_info'] : config::get('',$v['conf_info']);
                $object = new $k($conf);
            }

            if(!method_exists($object,$v['exec_func']))
                throw new middlewareError("call to undefined function {$v['exec_func']}");

            if(!isset($v['call_pass']) || empty($v['call_pass']))
                $res = call_user_func([$object,$v['exec_func']]);
            else
            {
                if(is_array($v['call_pass']))
                    $res = call_user_func_array([$object,$v['exec_func']],$v['call_pass']);
                else
                    $res = call_user_func([$object,$v['exec_func']],$v['call_pass']);
            }

            if($res !== true)
            {
                $note = (is_string($res) ? $res : ($v['throw_msg'] ? : 'execute error.'));
                raise($note , 600 );
            }
        });

        return true;
    }

    private static function loadCoreMiddleware()
    {
        //是否载入防火墙配置
        if(config::get('firewall') === true)
        {
            self::$services[self::BGR]["\\middleware\\firewall"] = [
                'exec_func'=>'execute',
                'conf_info'=>config::get('firewall_config'),
                'throw_msg'=>'访问被禁止, Access forbid.',
                'call_pass'=>'',
            ];
        }

        return true;
    }
}
