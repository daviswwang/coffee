<?php

use services\abnormal;

//定义基础信息
define('C_ROOT',dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR);
define('C_VENDOR',dirname(__DIR__).DIRECTORY_SEPARATOR);
define('C_ITEM',$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR);
define('C_NAME','coffee');
define('C_VERSION','V1.0 beta');
define('C_EXT','.php');

//初始化自动载入
require_once C_VENDOR."autoload.php";

//监听异常
abnormal::listen();

try
{
    $a = \services\config::get('restriction');
    $klein = new \Klein\Klein();

    $klein->respond('/[:name]', function ($request) {
        print_r($request->name);

    });

    $klein->dispatch();
}
catch (\Exception $e)
{
    throw new \coffee\exception\systemError($e->getMessage(),$e->getCode());
}





//监听请求

//监听防火墙

//监听中间件

//监听路由

//监听响应



//error_reporting(E_ALL);
//ini_set('display_errors',1);
//
////请求执行 获取项目配置文件
//\services\config::set('config2');

//print_r(di());
//di()->setConfig(C_ITEM.'config'.DIRECTORY_SEPARATOR.'config.php');
//
//if(!di()->getConfig())

//print_r($config);

//
//$w = new \Whoops\Handler\JsonResponseHandler();
//
//
//(new \Whoops\Run)->pushHandler($w)->register();
//
//
//di()->setConfig([1,2,3,4,5]);
//
//print_r(di()->getConfig());
//
//di()->xxx(0);



//载入配置文件
//$config = require_once C_ITEM.'config/config.php';

//print_r($config);





//    throw new Exception("路由无匹配项 404 Not Found");
//
//    print_r(new Illuminate\Database\Capsule\Manager);
    
//new coffee\test();

//print_r($_SERVER);