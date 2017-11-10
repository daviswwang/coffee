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

//初始化项目异常捕捉机制
(new \coffee\abnormal)->listen();

//应用注入并初始化
di('request')->register($auto);

//中间件初始化机制
(new \coffee\middleware)->listen();

//初始化路由模块
(new \coffee\route)->listen();

//监听响应
return di('response')->listen();