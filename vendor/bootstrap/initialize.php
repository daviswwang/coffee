<?php

//定义基础信息
define('C_ROOT',dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR);
define('C_VENDOR',dirname(__DIR__).DIRECTORY_SEPARATOR);
define('C_ITEM',$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR);
define('C_NAME','coffee');
define('C_VERSION','V1.0 beta');
define('C_EXT','.php');

//初始化自动载入
$auto = require_once C_VENDOR."autoload.php";

//收到请求 -- 注入项目
di()['request']->register();

//监听异常
services\abnormal::listen();

//监听中间件
//\services\middleware::listen();

//路由监听
//\services\route::listen();

//监听防火墙

//监听响应