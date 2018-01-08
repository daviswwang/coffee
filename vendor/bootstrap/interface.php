<?php

//定义基础信息
define('C_ROOT',dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR);
define('C_VENDOR',dirname(__DIR__).DIRECTORY_SEPARATOR);
define('C_COMPONENT',C_VENDOR.'component'.DIRECTORY_SEPARATOR);
defined('C_ITEM') ? : define('C_ITEM',dirname(dirname(debug_backtrace()[0]['file'])).DIRECTORY_SEPARATOR);
define('C_NAME','coffee');
define('C_VERSION','V1.0 beta');
define('C_EXT','.php');

//开启全部错误
error_reporting(E_ALL);
ini_set('display_errors',1);

$auto = require_once C_VENDOR."autoload.php";

//应用注入并初始化
di('request')->register($auto);

//不限制执行时间
set_time_limit(0);

//用户链接中断,脚本继续执行
ignore_user_abort(true);