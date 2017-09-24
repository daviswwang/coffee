<?php

namespace coffee;

class container implements \ArrayAccess
{

    //服务注入池
    private $servicesPool = [];

    //服务命中数
    private $hit = [];

    //容器初始化
    private static $instance = NULL;

    //初始化
    public static function initialize()
    {
        if(is_null(static::$instance))
        {
            static::$instance = new container();
        }

        return static::$instance;
    }

    //服务注入
    public function set()
    {

    }

    //获取服务
    public function get()
    {

    }

    //array 方式服务注入
    public function offsetSet($offset,$value)
    {
        return $this->set($offset,$value);
    }

    //array 检查服务是否存在
    public function offsetExists($offset)
    {
        return isset($this->servicesPool[$offset]);
    }

    //array 销毁服务
    public function offsetUnset($offset) {
        unset($this->servicesPool[$offset]);
    }

    //array 方式获取服务
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
}