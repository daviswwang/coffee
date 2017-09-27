<?php

namespace coffee;

class request extends container
{
    public function register()
    {
        $name = basename(rtrim(str_replace("\\",'/',C_ITEM),'/'))."\\";
        $namespace = [$name=>C_ITEM];

        if(!($configNamespace = \services\config::get('namespace')))
        {
            $namespace = array_merge($namespace,$configNamespace);
        }

        print_r($namespace);

        print_r(di());
    }
}