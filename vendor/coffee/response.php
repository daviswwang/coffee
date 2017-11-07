<?php

namespace coffee;

use services\middleware ;

class response
{
    private $app = NULL;

    private $result = NULL;

    public function listen()
    {
        $this->app = di('request')->getApplication();
        return $this;
    }

    public function run()
    {
        middleware::callback(middleware::BEA);
        $this->result = call_user_func([new $this->app(),di('request')->getAction()]);
        middleware::callback(middleware::AEA);
        
        return $this;
    }

    public function send()
    {
        if($this->result) raise('success',0,$this->result);
    }
}