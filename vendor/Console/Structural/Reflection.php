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

use Coffee\Exception\SystemException;

/* @class Reflection
 * @desc  映射处理,处理无法正常处理的类
 * @job   完成正常类无法完成的任务
 * */
class Reflection
{

    private $reflection = NULL;

    private $setObject  = NULL;

    private $objectName = NULL;

    public function initObject ( $obj = NULL , $obj_name = '' )
    {

        if ( !is_object( $obj ) )

            throw new SystemException( "reflection set object is not valid object." );

        $this->setObject  = $obj;

        $this->reflection = new \ReflectionClass ( $this->setObject );

        $this->objectName = $obj_name;

        return $this;
    }

    public function setPrivateAttribute( $set = '' , $val = NULL )
    {
        if ( !$this->reflection->hasProperty( $set ) )

            throw new SystemException('attribute is undefined at ' . $set );

        $attribute = $this->reflection->getProperty( $set );

        if ( !$attribute->isPrivate() )

            throw new SystemException('this attribute is not private at '. $set );

        try
        {
            $attribute->setAccessible ( true );
            $attribute->setValue ( $this->setObject , $val );
            Facade::getInstance()[
                Facade::getInstance()['ServicesParsing']->namespaceToClassName ( $this->objectName )
            ] = $this->setObject;
        }
        catch (\ReflectionException $e)
        {
            throw new SystemException ( $e->getMessage() );
        }
    }
}