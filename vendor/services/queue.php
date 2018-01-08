<?php

namespace services;

class queue
{

    private static $queue_pool = [];

    public static function memcache($conf = 'default')
    {

        if(isset(self::$queue_pool[$conf])) return self::$queue_pool[$conf];

        //得到队列
        $queue = new \component\queue\memcache(config::get($conf,'queue'));

        self::$queue_pool[$conf] = $queue;

        return $queue;
    }


}