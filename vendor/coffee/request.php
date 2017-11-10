<?php

namespace coffee;

use coffee\exception\requestError;

class request extends container
{

    private $service = [];

    public function register($obj = NULL)
    {
        $name = basename(rtrim(str_replace("\\",'/',C_ITEM),'/'))."\\";
        $namespace = [$name=>C_ITEM];

        if(!($configNamespace = \services\config::get('app.namespace')))
        {
            $namespace = array_merge($namespace,$configNamespace);
        }

        if($namespace)
            array_walk($namespace,function($v,$k)use($obj){$obj->setPsr4($k,$v);});

        if($autoload = \services\config::get('app.autoload'))
            array_map(function($v){
                if(file_exists($v)) require $v; else throw new requestError("autoload file $v is not exists");
            },$autoload);

        $obj->register(true);

        return true;
    }

    public function get_path_info()
    {
        if(isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO']))
        {
            $path_info = $_SERVER['PATH_INFO'];
        }
        elseif(isset($_SERVER['PHP_SELF']) && !empty($_SERVER['PHP_SELF']))
        {
            $path_info = $_SERVER['PHP_SELF'];
        }
        else
        {
            $path_info = rtrim(strstr($_SERVER['REQUEST_URI'],'?',true),'?');
        }

        if(empty($path_info))
            $path_info = '/';

        return $path_info;
    }

    public function get_namespace()
    {
        return isset($this->service['namespace']) ? $this->service['namespace'] : NULL;
    }

    public function get_class()
    {
        return isset($this->service['class']) ? $this->service['class'] : NULL;
    }

    public function get_action()
    {
        return isset($this->service['action']) ? $this->service['action'] : NULL;
    }

    public function get_status()
    {
        return isset($this->service['status']) ? $this->service['status'] : NULL;
    }

    public function get_application()
    {
        return isset($this->service['application']) ? $this->service['application'] : NULL;
    }
}