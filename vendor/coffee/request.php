<?php

namespace coffee;

class request extends container
{

    private $service = [];

    public function register($obj = NULL)
    {
        $name = basename(rtrim(str_replace("\\",'/',C_ITEM),'/'))."\\";
        $namespace = [$name=>C_ITEM];

        if(!($configNamespace = \services\config::get('namespace')))
        {
            $namespace = array_merge($namespace,$configNamespace);
        }

        if($namespace)
            array_walk($namespace,function($v,$k)use($obj){$obj->setPsr4($k,$v);});

        $obj->register(true);

        return true;
    }

    public function getNamespace()
    {
        return isset($this->service['namespace']) ? $this->service['namespace'] : NULL;
    }

    public function getClass()
    {
        return isset($this->service['class']) ? $this->service['class'] : NULL;
    }

    public function getAction()
    {
        return isset($this->service['action']) ? $this->service['action'] : NULL;
    }

    public function getStatus()
    {
        return isset($this->service['status']) ? $this->service['status'] : NULL;
    }

    public function getApplication()
    {
        return isset($this->service['application']) ? $this->service['application'] : NULL;
    }
}