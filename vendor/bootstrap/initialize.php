<?php

//定义基础信息
define('C_ROOT',dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR);
define('C_NAME','coffee');
define('C_VERSION','V1.0 beta');
define('C_VENDOR',dirname(__DIR__));
define('C_ITEM',$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR);

require_once C_VENDOR."/autoload.php";

//载入配置文件
$config = require_once C_ITEM.'config/config.php';

//print_r($config);

//$w = new \Whoops\Handler\PlainTextHandler();
//
//
//(new \Whoops\Run)->pushHandler($w)->register();



//    throw new Exception("路由无匹配项 404 Not Found");
//
    print_r(new Illuminate\Database\Capsule\Manager);
    
//new coffee\test();

//print_r($_SERVER);