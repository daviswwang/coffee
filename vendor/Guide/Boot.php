<?php

/* +-------------------------------------------------------
 * | Coffee PHP Framework Version 2.0.0                   +
 * +-------------------------------------------------------
 * | Copyright (c) 2017 Coffee All rights reserved.       +
 * +-------------------------------------------------------
 * | Git Link ( https://github.com/naofbear/coffee )      +
 * +-------------------------------------------------------
 * | Author : huakaiquan Email : <huakaiquan@qq.com>      +
 * +-------------------------------------------------------
 * | Licensed(http://www.apache.org/licenses/LICENSE-2.0) +
 * +-------------------------------------------------------
 * | 拒绝臃肿、快速开发、简单上手、即为 Coffee 初心              +
 * | Welcome to join us to make it easier and easier.     +
 * +-------------------------------------------------------
 * */

//设置核心框架名称
define( 'COFFEE_NAME' , 'coffee framework' );

//设置框架版本号
define( 'COFFEE_VERSION' , '2.0.0' );

//定义全局文件名后缀名
define( 'COFFEE_SUFFIX' , '.php' );

//定义核心Vendor运行目录
define ( 'COFFEE_VENDOR' , dirname ( __DIR__ ) . DIRECTORY_SEPARATOR );

//引入COMPOSER 自动加载
include_once ( COFFEE_VENDOR . "autoload.php" );

//解析当前执行模式
define ( 'COFFEE_RUN_MODE' , \services\parsing::getNowRunMode ( ) );

//设置应用执行目录
if ( COFFEE_RUN_MODE == 'web' )

    define( 'COFFEE_APP_PATH' , \services\request::getServer( 'DOCUMENT_ROOT' ) . DIRECTORY_SEPARATOR );

else
{

    if( !defined( 'COFFEE_APP_PATH' ) )

        exit( ' Please define "COFFEE_APP_PATH". ' );

}

//设置应用执行模式
define ( 'COFFEE_APP_MODE' , \services\config::get ( 'app.mode' ) );

//载入项目异常捕捉机制
\Coffee\Console\Structural\Abnormal::listen();



echo COFFEE_RUN_MODE;
