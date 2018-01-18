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

namespace Coffee\Console\Frame;
use \Whoops\Handler\XmlResponseHandler ;
use \Whoops\Handler\JsonResponseHandler;
use \Whoops\Handler\PrettyPageHandler;
use \Whoops\Handler\PlainTextHandler;
use \Whoops\Util\Misc;
use \Coffee\Console\Structural\Facade;

/* @class StructuralAbnormal
 * @desc  异常监听类
 * @job   监听系统运行出现异常并捕捉抛出此异常
 * */
class Abnormal
{

    /* @func listen
     * @desc 异常监听
     * */
    public static function listen ()
    {
        if ( COFFEE_RUN_CLI )
            $mode = 'cli';
        else
            $mode = COFFEE_APP_MODE;

        if ( !in_array( $mode , [ 'api' , 'html' , 'cli' ] ) )
            $mode = 'api';

        if ( Facade::getInstance()['ServicesConfig']->get('debug.switch') )
        {
            error_reporting(E_ALL);
            ini_set('display_errors',1);
        }

        return ( new \Whoops\Run() )->pushHandler ( self::getHandle( $mode ) )->register();
    }

    /* @func getHandle
     * @desc 根据不同运行模式获取不同的句柄
     * @param $mode <运行模式>
     * @return <Handle - Object>
     * */
    private static function getHandle ( $mode )
    {
        switch ( $mode )
        {
            case 'html':
                $handle = Misc::isAjaxRequest() ? new JsonResponseHandler() : new PrettyPageHandler();
                break;
            case 'cli':
                $handle = new PlainTextHandler();
                break;
            default:
                $handle = Facade::getInstance()[ 'ServicesConfig' ]->get( 'app.api.format' ) == 'xml'
                    ? new XmlResponseHandler()
                    : new JsonResponseHandler();
                break;
        }

        if ( method_exists ( $handle , 'addTraceToOutput' ) )
            $handle->addTraceToOutput ( true );

        if ( method_exists ( $handle , 'setPageTitle' ) )
            $handle->setPageTitle( '异常信息 - '.config::get( 'app.name' ) );

        return $handle;
    }
}