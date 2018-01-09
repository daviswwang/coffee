<?php

namespace coffee;

use services\config;
use services\input;
use services\request as r;
use services\view;

class response
{
    private $app = NULL;

    private $result = NULL;

    private $obj = NULL;

    public function listen()
    {
        $this->app = di('request')->get_application();
        return $this;
    }

    public function run()
    {
        //中间件组件 应用执行之前操作
        di('middleware')->callback('BEFORE_EXEC_APP');

        $object = new $this->app();
        $action = r::get_action();

        if(!method_exists($object,$action))
        {
            $app = "\\".(config::get('app.name'))."\\".(config::get('app.dir'))."\\".config::get('app.default.404_class');
            $object = new $app();
            $action = config::get('app.default.404_action');
        }

        $this->obj = $object;

        $this->result = call_user_func([$object,$action]);
        //中间件组件 应用执行之后操作
        di('middleware')->callback('AFTER_EXEC_APP');
        
        return $this;
    }

    public function raise()
    {
        $res = $this->result;
        di('reflection')->object(di('response'))->setPrivateAttribute('result',NULL);
        return $res ? : false;
    }

    public function send($return = false)
    {
        if($return) return $this->result;

        if(config::get('app.mode') == 'api')
            raise('success',0,$this->result);
        elseif(
            config::get('app.mode') == 'html' &&
            config::get('app.view.autoload') &&
            !input::is_ajax() &&
            r::get_class() != config::get('app.default.404_class') &&
            method_exists($this->obj,r::get_action())
        )
            view::display(r::get_class().'/'.r::get_action());

    }
}