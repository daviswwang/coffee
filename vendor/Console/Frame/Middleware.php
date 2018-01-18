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

use \Coffee\Console\Structural\Facade;
use \Coffee\Exception\EventException;

/* @class Middleware
 * @desc  事件管理器
 * @job   用户管理事件并分发执行事件
 * */
class Middleware
{

    /* @attribute $_enable
     * @desc 记录事件是否启动
     * */
    private static $_enable = true;

    /* @func listenWithStart
     * @desc 监听事件并启动分发事件
     * @return boolean
     * */
    public static function listenWithStart ()
    {

        if ( !Facade::getInstance()['ServicesConfig']->get ( 'component.middleware' ) )

            self::$_enable = false;

        return self::$_enable;

    }

    /* @func executeCallback
     * @desc 事件分发处理执行
     * @param $service_note <事件服务节点>
     * @return <执行结果 boolean | exit>
     * */
    public static function executeCallback ( $service_note = '' )
    {
        if ( !self::$_enable || !$service_note )
            return true;

        $componentCallbackServiceNote = Facade::getInstance()['ServicesConfig']->get ( 'component.middleware' );

        if ( !$componentCallbackServiceNote )
            return true;

        if( !isset( $componentCallbackServiceNote[ $service_note ] ) || empty( $componentCallbackServiceNote[ $service_note ] ) )
            return true;
        else
            $executeCallbackServiceNote = $componentCallbackServiceNote[ $service_note ];

        if( isset( $executeCallbackServiceNote[ 'is_enable' ] ) && !$executeCallbackServiceNote[ 'is_enable' ] )
            return true;

        array_walk( $executeCallbackServiceNote , function ( $v , $k ) {

            if ( empty( $v[ 'exec_func' ] ) )
                $v[ 'exec_func' ] = 'exec';

            $storageContainerName = 'Middleware_'.md5( $k );

            if ( Facade::getInstance()->offsetExists( $storageContainerName ) )
                $nowMiddlewareObject = Facade::getInstance()[ $storageContainerName ];
            else
            {
                if ( class_exists( $k ) )
                    throw new EventException( "this middleware >>> $k is not exits" );

                if( empty( $v[ 'conf_info' ] ) )
                    $nowMiddlewareObject = new $k ();
                else
                {
                    if ( is_array( $v[ 'conf_info' ] ) )
                        $nowMiddlewareObject = new $k ( $v[ 'conf_info' ] );
                    else
                        $nowMiddlewareObject = new $k (
                            Facade::getInstance()['ServicesConfig']->get ( NULL , $v[ 'conf_info' ] )
                        );
                }

                Facade::getInstance()[ $storageContainerName ] = $nowMiddlewareObject;
            }

            if ( !method_exists( $nowMiddlewareObject , $v[ 'exec_func' ] ) )
                throw new EventException( "call to undefined function {$k}::{$v[ 'exec_func' ]}" );

            if ( empty( $v[ 'call_pass' ] ) )
                $executeResponseResult = call_user_func(
                    [ $nowMiddlewareObject , $v[ 'exec_func' ] ]
                );
            else
            {
                if ( is_array( $v[ 'call_pass' ] ) )
                    $executeResponseResult = call_user_func_array(
                        [ $nowMiddlewareObject , $v[ 'exec_func' ] ] , $v[ 'call_pass' ]
                    );
                else
                    $executeResponseResult = call_user_func(
                        [ $nowMiddlewareObject , $v[ 'exec_func' ] ] , $v[ 'call_pass' ]
                    );
            }

            if ( $executeResponseResult !== true )
            {
                $tryResponseNote = is_string( $executeResponseResult )
                    ? $executeResponseResult
                    : ( empty( $v[ 'throw_msg' ] ) ? 'execute error.' : $v[ 'throw_msg' ] );

                raise( $tryResponseNote , 600 );
            }
            
        } );

        return true;
    }
}