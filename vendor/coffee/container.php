<?php

namespace coffee;

use \Closure , coffee\exception\interiorError;

class container implements \ArrayAccess
{

    //服务注入池
    protected $servicesPool = [];

    //服务命中数
    protected $hit = [];

    //容器初始化
    private static $instance = NULL;

    //初始化
    public static function initialize()
    {
        if(is_null(static::$instance))
        {
            static::$instance = new container();
            static::$instance->loadBase();
        }

        return static::$instance;
    }

    public function loadBase()
    {
        $base = [
            'loader'=>'\\coffee\\loader',
            'request'=>'\\coffee\\request',
            'response'=>'\\coffee\\response',
            'reflection'=>'\\coffee\\reflection'
        ];

        foreach ($base as $k=>$v) $this->$k = $v;
    }

    //服务注入
    public function set($key , $val)
    {
        //重设命中次数
        $this->resetHit($key);

        $this->servicesPool[$key] = $this->initService($val);

        if($this->servicesPool[$key]) return true; else return false;
    }

    //获取服务
    public function get($key)
    {
        //增加命中次数
        $this->recordHit($key);

        return unserialize($this->servicesPool[$key]);
    }

    //初始化服务
    private function initService($service = NULL)
    {
        if(!$service) return false;

        if($service instanceof Closure)
            $service = $service();
        elseif(is_string($service) && class_exists($service))
            $service = new $service();

        return serialize($service);
    }

    public function __call($name, $arguments)
    {
        $prefix = strtolower(substr($name,0,3));

        $key    = lcfirst(substr($name,3));

        switch ($prefix)
        {
            case 'set':
                return $this->set($key , $arguments[0] ? : NULL);
            case 'get':
                return $this->get($key);
        }

        throw new interiorError('Call to undefined method container::'.$name);
    }

    //魔术方法 set
    public function __set($name, $val)
    {
        return $this->set($name , $val);
    }

    //魔术方法 get
    public function __get($name)
    {
        return $this->get($name);
    }

    //记录命中服务
    protected function recordHit($key)
    {
        if(!$this->isHit($key))
            $this->resetHit($key);

        return $this->hit[$key] ++;
    }

    //重设服务命中次数
    protected function resetHit($key)
    {
        $this->hit[$key] = 0;
    }

    //是否是已命中服务
    protected function isHit($key)
    {
        if(isset($this->hit[$key]) && $this->hit[$key] >= 0)
            return true;
        return false;
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
    public function offsetUnset($offset)
    {
        unset($this->servicesPool[$offset]);
    }

    //array 方式获取服务
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
}