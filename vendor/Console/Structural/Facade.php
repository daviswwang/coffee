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

namespace Coffee\Console\Structural;


/* @class StructuralFacade
 * @desc  伪装类
 * @job   伪式引用指定对象
 * */
class Facade extends Container
{

    /* @attribute $baseFunction
     * @desc 继承伪装类必须存在的方法名称
     * */
    private static $baseFunction = '_getAccessObjectName';

    /* @func __callStatic
     * @desc 魔术方法 __callStatic
     * @return <Object>
     * */
    public static function __callStatic( $name , $arguments )
    {

        return call_user_func_array( [

            self::getInstance()[ call_user_func ( implode ( '::' , [ get_called_class() , self::$baseFunction ] ) ) ] , $name

        ] , $arguments );
        
    }
}