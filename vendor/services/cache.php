<?php

namespace services;

use coffee\exception\cacheError;

class cache
{

    private static $cache_pool = [];

    public static function connect($conf = 'default')
    {

        if(isset(self::$cache_pool[$conf])) return self::$cache_pool[$conf];

        //得到对应数据库配置
        $set = config::get($conf,'cache');

        if(!$set) throw new cacheError('cache config is error.');

        switch($set['driver'])
        {
            case 'memcached':
                    $cache = new \Memcached();
                    $cache->addServer($set['host'],$set['port']);
                    self::$cache_pool[$conf] = $cache;
                break;
        }

        if(!isset(self::$cache_pool[$conf])) throw new cacheError('new cache object is error.');

        return $cache;
    }

    public static function disconnect($conf = 'default')
    {
        unset(self::$cache_pool[$conf]);
        return true;
    }
}