<?php

//定义基础信息
define('C_ROOT',dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR);
define('C_VENDOR',dirname(__DIR__).DIRECTORY_SEPARATOR);
define('C_COMPONENT',C_VENDOR.'component'.DIRECTORY_SEPARATOR);
defined('C_ITEM') ? : define('C_ITEM',$_SERVER['PWD'].DIRECTORY_SEPARATOR);
define('C_NAME','coffee');
define('C_VERSION','V1.0 beta');
define('C_EXT','.php');
define('RUN_MODE','socket');

//初始化自动载入
$auto = require_once C_VENDOR."autoload.php";
               
//初始化项目异常捕捉机制
(new \coffee\abnormal)->listen();

//应用注入并初始化
di('request')->register($auto);

if(!class_exists('\component\socket\websocket'))
    exit("please install socket component.\n");

switch(\services\config::get('mode','socket'))
{
    case 'websocket':
        //socket启动
        (new component\socket\websocket)->listen();
        break;
    default:
        exit('socket mode is not support.');
}
