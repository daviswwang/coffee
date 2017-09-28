<?php

namespace services;

class middleware
{
    const BGR = 'BEFORE_GET_ROUTE';

    const AGR = 'AFTER_GET_ROUTE';

    const BEA = 'BEFORE_EXEC_APP';

    const AEA = 'AFTER_EXEC_APP';

    const BNF = 'BEFORE_NOT_FOUND';

    const ANF = 'AFTER_NOT_FOUND';


    private static $services = [

        'BEFORE_GET_ROUTE'  =>[],

        'AFTER_GET_ROUTE'   =>[],

        'BEFORE_EXEC_APP'   =>[],

        'AFTER_EXEC_APP'    =>[],

        'BEFORE_NOT_FOUND'  =>[],

        'AFTER_NOT_FOUND'   =>[],
        
    ];

    private static $enable = false;

    public static function listen()
    {
        //载入核心中间件
        self::loadCoreMiddleware();

        //判断是否启用中间件
        if(config::get('enable_middleware') === true)
        {

        }
    }

    private static function loadCoreMiddleware()
    {
        //是否载入防火墙配置
        if(config::get('enable_firewall') === true)
        {
            self::$services = array_merge(["\\middleware\\test"=>[
                'exec_func'=>'exec',
                'conf_info'=>'firewall',
                'throw_msg'=>'访问被禁止, Access forbid.',
                'call_pass'=>'',
            ]],self::$services[self::BGR]);
        }

        return true;
    }


}
