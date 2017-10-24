<?php

namespace coffee;

class application
{
    private static $app = NULL;

    private $result = NULL;

    public static function listen()
    {
        self::$app = di('request')->getApplication();

        return new application();
    }

    public function run()
    {
        $this->result = call_user_func([new static::$app(),di('request')->getAction()]);
        return $this;
    }

    public function send()
    {
        if($this->result) raise('success',0,$this->result);
    }
}