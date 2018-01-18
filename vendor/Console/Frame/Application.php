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
use \Coffee\Exception\FrameException;

/* @class Application
 * @desc  应用类
 * @job   维系应用统一性
 * */
class Application
{

    /* @attribute $_load
     * @desc 可载入的服务
     * */
    private $_load = [
        'config','interpret','parsing','request','http','input','cache','db','cookie','session','view'
    ];

    /* @attribute $_is_new
     * @desc 记录此类是否被实例化
     * */
    private static $_is_new = false;

    /* @func __construct
     * @desc 记录已被实例化
     * */
    public function __construct ()
    {
        static::$_is_new  = true;
    }

    /* @func listenWithRegister
     * @desc 监听并注入应用
     * @param $autoloadObject <Composer 对象>
     * @return boolean
     * */
    public static function listenWithRegister ( $autoloadObject = NULL , $listenMiddleware = true , $listenRoute = true )
    {

        if ( self::$_is_new )

            throw new FrameException( "It is prohibited to use." );

        $_registerApplicationNamespace = [
            
            Facade::getInstance()['ServicesConfig']->get ( 'app.name' ) . "\\" => COFFEE_APP_PATH
        ];

        if( $_diyApplicationNamespace = Facade::getInstance()['ServicesConfig']->get ( 'app.namespace' ) )

            $_registerApplicationNamespace = array_merge(

                $_diyApplicationNamespace , $_registerApplicationNamespace

            );

        if( $_registerApplicationNamespace )

            array_walk( $_registerApplicationNamespace ,
                function ( $v , $k ) use ( $autoloadObject )
                {
                    $autoloadObject->setPsr4( $k , $v );
                }
            );

        if( $_diyApplicationAutoload = Facade::getInstance()['ServicesConfig']->get ( 'app.autoload' ) )
            
            array_map( function ( $v ) {
                if ( file_exists( $v ) )
                    Facade::getInstance()['Loader']->file ( $v );
                else
                    throw new FrameException( "autoload file $v is not exists." );
            } , $_diyApplicationAutoload );

        $autoloadObject->register ( true );

        if ( $listenMiddleware )
            \Coffee\Console\Frame\Middleware::listenWithStart();

        if ( $listenRoute )
            \Coffee\Console\Frame\Route::listenWithParse();

        if( $listenMiddleware && $listenRoute )
            return Facade::getInstance()[ 'ServicesResponse' ];

        return true;
    }

    /* @func __get
     * @desc 魔术方法 __get 处理不可见对象
     * @return <Object>
     * */
    public function __get($name)
    {
        if( in_array( $name , $this->_load ) )

            return Facade::getInstance()[ implode ( '' , array_map (  'ucfirst' , [ 'services' , $name ] ) ) ];

        return NULL;
    }
}