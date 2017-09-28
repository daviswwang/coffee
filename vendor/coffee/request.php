<?php

namespace coffee;

class request extends container
{
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
}