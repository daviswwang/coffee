<?php

namespace services;

use coffee\exception\interiorError;

class config
{
    public static function set($filename = '')
    {
        //设置配置路径
        $loadFile = C_ITEM.'config'.DIRECTORY_SEPARATOR.$filename.C_EXT;

        //检测配置文件是否存在
        if(!file_exists($loadFile))
            throw new interiorError("config $filename is not exists.");

        $fun = $filename == 'config' ? $filename : $filename.'_config';

        //注入服务池
        return di()->{'set'.$fun}(di()['loader']->fileHaveReturn($loadFile));
    }

    public static function get($key = '' , $filename = '')
    {
        //是否取指定文件配置
        if($filename)
        {
            if(isset(di()[$filename.'_config']))
                $config = di()[$filename.'_config'];
            else
            {
                if(config::set($filename))
                    $config = di()[$filename.'_config'];
                else
                    $config = [];
            }
        }
        else
        {
            if(isset(di()['config']))
                $config = di()['config'];
            else
            {
                if(config::set('config'))
                    $config = di()['config'];
                else
                    $config = [];
            }
                    
        }

        if(!$config) return [];

        if($key)
        {
            $config = self::get_config_by_dot($key,$config);
        }

        return $config;
    }

    private static function get_config_by_dot($key = '' , $config = [])
    {
        if(strstr($key,'.'))
        {
            foreach (explode('.',$key) as $v)
            {
                if(!isset($config[$v])) continue;

                $config = self::get_config_by_dot($v,$config);
            }
        }
        elseif(isset($config[$key])) $config = $config[$key];
        else $config = [];

        return $config;
    }
}