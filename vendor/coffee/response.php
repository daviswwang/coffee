<?php

namespace coffee;

use services\request;

class response
{
    private $app = NULL;

    private $result = NULL;

    public function listen()
    {
        $this->app = di('request')->get_application();
        return $this;
    }

    public function run()
    {
        //中间件组件 应用执行之前操作
        di('middleware')->callback('BEFORE_EXEC_APP');
        $this->result = call_user_func([new $this->app(),request::get_action()]);
        //中间件组件 应用执行之后操作
        di('middleware')->callback('AFTER_EXEC_APP');
        
        return $this;
    }

    public function send()
    {
        raise('success',0,$this->result);
    }
}