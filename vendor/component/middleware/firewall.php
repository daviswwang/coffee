<?php

namespace middleware;

class firewall
{
    private $config = [];

    public function __construct($config = [])
    {
        $this->config = $config;
    }


    public function execute()
    {
//        print_r($this->config);
        return false;
    }
}