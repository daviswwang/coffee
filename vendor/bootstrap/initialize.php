<?php

use \services\abnormal , \services\middleware , \services\route , \coffee\application;

//定义基础信息
define('C_ROOT',dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR);
define('C_VENDOR',dirname(__DIR__).DIRECTORY_SEPARATOR);
define('C_ITEM',$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR);
define('C_NAME','coffee');
define('C_VERSION','V1.0 beta');
define('C_EXT','.php');

//初始化自动载入
$auto = require_once C_VENDOR."autoload.php";

//监听异常
abnormal::listen();

//监听中间件
middleware::listen();

//路由监听
route::listen();

//监听响应
return application::listen();