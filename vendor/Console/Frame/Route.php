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

/* @class Route
 * @desc  路由处理类
 * @job   解析Request得到的路由信息
 * */
class Route
{

    /* @func listenWithParse
     * @desc 监听并解析得到的路由
     * */
    public static function listenWithParse ()
    {
        Middleware::executeCallback( 'BEFORE_GET_ROUTE' );

        self::checkRouteParseMode( Facade::getInstance()[ 'ServicesConfig' ]->get ( NULL , 'route' ) );
    }

    /* @func checkRouteParseMode
     * @desc 检测路由解析模式/不同模式不同解析规则
     * @param $route array <路由配置>
     * */
    private static function checkRouteParseMode ( array $route = [] )
    {
        if ( empty( $route ) || Facade::getInstance()['ServicesConfig']->get ( 'component.route' ) === false )
            $routeParseResult = self::parseRouteWithPathInfo ( Facade::getInstance()[ 'ServicesRequest' ]->getPathInfo() );
        else
        {
            if ( !class_exists( "\\Coffee\\Extension\\Route\\Match" ) )

                throw new FrameException( "Please install Route/Match Component." );

            $routeParseResult = self::parseRouteWithPathInfo (

                (new \Coffee\Exception\Route\Match( $route ))->parseRouteGetNewPathInfo(

                    Facade::getInstance()[ 'ServicesConfig' ]->getPathInfo()
                )
            );
        }

        Facade::getInstance()['Reflection']->initObject(
            Facade::getInstance()['ServicesRequest'] , 'ServicesRequest'
        )->setPrivateAttribute( 'service' , $routeParseResult );

        Middleware::executeCallback( 'AFTER_GET_ROUTE' );
    }

    /* @func parseRouteWithPathInfo
     * @desc 根据请求的PathInfo解析路由
     * @param $pathInfo string <PATH_INFO>
     * @return array
     * */
    private static function parseRouteWIthPathInfo ( $pathInfo = '' )
    {

        $parseNamespace = $routeNamespace = "\\". implode( "\\" , [
            Facade::getInstance()['ServicesConfig']->get ( 'app.name' ) ,
            Facade::getInstance()['ServicesConfig']->get ( 'app.dir' )
        ] ). "\\";

        $routeResponseStatusCode = 200;

        if ( empty ( $pathInfo ) || $pathInfo == '/' )
        {
            $parseApplicationClass  = Facade::getInstance()['ServicesConfig']->get ( 'app.default.app_class' );
            $parseApplicationAction = Facade::getInstance()['ServicesConfig']->get ( 'app.default.app_action' );
        }
        else
        {

            $splitPathInfoGetProjectPath = explode( '/' , trim ( $pathInfo , '/' ) );
            $filePath  = COFFEE_APP_PATH . Facade::getInstance()['ServicesConfig']->get ( 'app.dir' ).DIRECTORY_SEPARATOR;
            $countProjectPathNum         = count( $splitPathInfoGetProjectPath );

            if ( count( $splitPathInfoGetProjectPath ) == 1 )
            {
                if ( file_exists( $filePath . $splitPathInfoGetProjectPath[0] . COFFEE_SUFFIX ) )
                {
                    $parseApplicationClass  = $splitPathInfoGetProjectPath[0];
                    $parseApplicationAction = Facade::getInstance()['ServicesConfig']->get ( 'app.default.app_action' );
                }
            }
            else
            {

                $parseApplicationClass = $parseApplicationAction = '';

                foreach ( $splitPathInfoGetProjectPath as $k => $v )
                {
                    if ( is_dir( $filePath . $v ) )
                    {
                        $parseNamespace .= $v . "\\";

                        if ( ( $k + 1 ) == $countProjectPathNum )
                        {
                            $parseApplicationClass  = Facade::getInstance()['ServicesConfig']->get ( 'app.default.app_class' );
                            $parseApplicationAction = Facade::getInstance()['ServicesConfig']->get ( 'app.default.app_action' );
                        }

                        $filePath .= $v . DIRECTORY_SEPARATOR;
                    }
                    elseif ( file_exists( $filePath . $v . COFFEE_SUFFIX ) )
                    {

                        $parseApplicationClass  = $v;

                        if ( ( $k + 1 ) == $countProjectPathNum )

                            $parseApplicationAction = Facade::getInstance()['ServicesConfig']->get ( 'app.default.app_action' );
                    }
                    elseif ( ( $k + 1 ) == $countProjectPathNum && !empty( $parseApplicationClass ) )

                        $parseApplicationAction = $v;

                }
            }
        }

        if ( empty( $parseApplicationClass ) && empty( $parseApplicationAction ))
        {
            $parseApplicationClass  = Facade::getInstance()['ServicesConfig']->get ( 'app.default.404_class' );
            $parseApplicationAction = Facade::getInstance()['ServicesConfig']->get ( 'app.default.404_action' );

            Middleware::executeCallback ( 'BEFORE_NOT_FOUND' );

            $routeResponseStatusCode = 404;
            $parseNamespace = $routeNamespace;
        }

        return [
            'namespace'     => $parseNamespace,
            'initNamespace' => $routeNamespace,
            'application'   => $parseNamespace . $parseApplicationClass,
            'class'         => $parseApplicationClass,
            'action'        => $parseApplicationAction,
            'status'        => $routeResponseStatusCode
        ];
    }
}