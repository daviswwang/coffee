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

use \Coffee\Exception\FrameException;

/* @class View
 * @desc  模板渲染
 * @job   渲染处理
 * */
class View
{

    private static $instance = NULL;

    public function getInstance()
    {
        if ( !static::$instance )
        {

            if ( !class_exists( "\\Coffee\\Extension\\View\\Template" ) )

                throw new FrameException("Please install View/Template Component.");

            static::$instance = new \Coffee\Extension\View\Template();
        }

        return static::$instance;
    }

    public function assign( $set , $val = NULL )
    {

        $this->getInstance()->assign( $set , $val );

        return $this->getInstance();
    }

    public function display( $file = '' , $set )
    {
        return $this->assign( $set )->display( $file );
    }

    public function render( $file = '' , $set )
    {
        return $this->assign( $set )->display( $file , true );
    }
}