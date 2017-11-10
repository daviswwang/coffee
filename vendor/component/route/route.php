<?php

namespace component\route;

class route
{
    private $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function match($request_path_info = '')
    {
        //是否存在基础匹配原则 /a -> /a/b
        if(isset($this->config[$request_path_info]))
            return $this->config[$request_path_info];

        
        return $request_path_info;
    }
}