<?php

namespace coffee;

use coffee\exception\middlewareError;
use services\config;

class middleware
{
    private $services = [

        'BEFORE_GET_ROUTE'  =>[],

        'AFTER_GET_ROUTE'   =>[],

        'BEFORE_EXEC_APP'   =>[],

        'AFTER_EXEC_APP'    =>[],

        'BEFORE_NOT_FOUND'  =>[]
        
    ];

    private $enable = false;

    public function listen()
    {
        //注入服务
        di()->setMiddleware($this);
        
        //是否初始化中间件模块
        if(\services\config::get('component.middleware') === false) return false;

        //执行初始化中间件模块并监听
        $this->services = array_merge_recursive($this->services,\services\config::get('','middleware'));
        $this->enable   = true;

        //重新注入服务
        di()->setMiddleware($this);

        return $this->enable;
    }

    public function callback($type = '')
    {
        if($this->enable === false || empty($this->services[$type])) return false;

        array_walk($this->services[$type],function($v,$k)
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
}
