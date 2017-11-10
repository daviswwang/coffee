<?php

namespace services;

use coffee\exception\mysqlError;

class db
{

    private static $db_pool = [];

    public static function connect($conf = 'default')
    {

        if(isset(self::$db_pool[$conf])) return self::$db_pool[$conf];

        //得到对应数据库配置
        $set = config::get($conf,'database');

        if(!$set) throw new mysqlError('mysql config is error.');

        switch($set['driver'])
        {
            case 'mysql':
                $pdo = new \PDO(
                    "{$set['driver']}:host={$set['host']};port={$set['port']};dbname={$set['database']};charset={$set['charset']}",
                    $set['username'],
                    $set['password']
                );

                require_once C_VENDOR."drives/database/NotORM.php";

                $structure = new \NotORM_Structure_Convention($set['primary'],$set['foreign'],$table = '%s',$set['prefix']);

                $db = new \NotORM($pdo , $structure);

                $db->jsonAsArray = true;

                self::$db_pool[$conf] = $db;

                break;
        }

        if(!isset(self::$db_pool[$conf])) throw new mysqlError('new db object is error.');

        return $db;
    }

    public static function disconnect($conf = 'default')
    {
        unset(self::$db_pool[$conf]);
        return true;
    }
}