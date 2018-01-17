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

namespace Coffee\Console\Http;
use Coffee\Console\Structural\Facade;

/* @class Request
 * @desc  请求处理类
 * @job   处理客户端发起的请求
 * */
class Request
{

    /* @attribute $service
     * @desc 存储必要信息
     * */
    private $service = [];

    /* @func getServer
     * @desc 获取Server值
     * @param $keyword <需要获取的KEY>
     * @return string
     * */
    public function getServer ( $keyword = '' )
    {

        if ( isset( $_SERVER[ strtoupper( $keyword ) ] ) )

            return $_SERVER [ strtoupper( $keyword ) ];

        return $_SERVER;
        
    }

    public function getApplication()
    {
        if( isset( $this->service ['application'] ) )

            return $this->service[ 'application' ];

        return false;
    }

    /* @func getPathInfo
     * @desc 得到请求PATH_INFO
     * @return string
     * */
    public function getPathInfo ()
    {

        $httpServerRequestPathInfo = $this->getServer( 'request_uri' );

        if ( strpos( $httpServerRequestPathInfo , Facade::getInstance()['ServicesConfig']->get ( 'app.default.inlet_file' ) ) )

            $httpServerRequestPathInfo = str_replace(
                '/' ,
                Facade::getInstance()['ServicesConfig']->get ( 'app.default.inlet_file' ) ,
                $httpServerRequestPathInfo
            );

        $httpRequestUrlSuffix = Facade::getInstance()['ServicesConfig']->get ( 'app.view.suffix' );

        if ( stripos( $httpServerRequestPathInfo , $httpRequestUrlSuffix ) )

            $httpServerRequestPathInfo = str_replace( $httpRequestUrlSuffix , '' , $httpServerRequestPathInfo );

        if ( empty( $httpServerRequestPathInfo ) || $httpServerRequestPathInfo == '/' )

            $httpServerRequestPathInfo = '/';

        elseif ( stripos( $httpServerRequestPathInfo , '?' ) )

            $httpServerRequestPathInfo = rtrim( strstr( $httpServerRequestPathInfo , '?' , true ) , '?' );

        return $httpServerRequestPathInfo;
    }
}