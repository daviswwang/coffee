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

namespace Coffee\Console\Service;

/* @class ServiceParsing
 * @desc  通用解析类
 * @job   解析数据
 * */
class Parsing
{

    /* @func getNowRunMode
     * @desc 获取当前运行模式
     * @return string
     * */
    public function getNowRunMode()
    {

        if( strpos ( strtolower ( PHP_SAPI ) , 'cli' ) ) return 'cli' ; else return 'web' ;

    }

    /* @func getConfig
     * @desc 为Config文件提供解析服务
     * @param $conf <解析值>
     * @param $array <数据源>
     * @return <string | array>
     * */
    public function getConfig( $conf , $array )
    {

        if( isset( $array[$conf] ) )

            return $array[$conf];

        elseif ( strpos( $conf , '.' ) )
        {

            foreach ( explode('.' , $conf ) as $v )
            {
                if( !isset( $array[$v] ) ) continue;

                $array = $this->getConfig ( $v , $array );
            }
        }

        return $array;
    }

    /* @func namespaceToClassName
     * @desc 命名空间转化为类名
     * @return string
     * */
    public function namespaceToClassName ( $namespace = '' )
    {

        if ( !$namespace )

            return $namespace;

        if ( strstr( $namespace , '\\' ) )
            return basename( str_replace( '\\' , '/' , $namespace ) );
        
        return basename( $namespace );
    }
}