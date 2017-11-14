<?php

namespace services;

class headers
{
    public static function get($key = '')
    {
        $key = strtoupper($key);
        if($key && isset($_SERVER['HTTP_'.$key])) return $_SERVER['HTTP_'.$key];else return '';
    }
}