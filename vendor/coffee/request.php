<?php

namespace coffee;

use coffee\exception\requestError;
use services\config;

class request extends container
{

    private $service = [];

    public function register($obj = NULL)
    {
        //设置时区
        date_default_timezone_set(config::get('app.default.app_timezone'));

        $namespace = [config::get('app.name')."\\"=>C_ITEM];

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
        $path_info = \services\request::get_server('request_uri');

        //过滤
        if(strpos($path_info,config::get('app.default.inlet_file')))
        {
            $path_info = str_replace('/'.config::get('app.default.inlet_file'),'',$path_info);
        }

        //伪静态
        $suffix = '.'.ltrim(config::get('app.view.suffix'),'.');
        if(strpos($path_info,$suffix))
        {
            $path_info = str_replace($suffix,'',$path_info);
        }

        if(empty($path_info) || $path_info == '/')
            $path_info = '/';
        elseif(stripos($path_info,'?'))
        {
            $path_info = rtrim(strstr($path_info,'?',true),'?');
        }

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