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

use Coffee\Console\Structural\Facade;
use Coffee\Exception\SystemException;

class Config
{
    public function get ( $conf = '' , $file = 'config' )
    {

        if( $file && $file != 'config' )

            $setContainerName = "{$file}_config";
        else

            $setContainerName = "config";

        if( !Facade::getInstance()->offsetExists( $setContainerName ) )
        {

            $loadFilePath = COFFEE_APP_PATH . "config" .DIRECTORY_SEPARATOR . $file .COFFEE_SUFFIX;

            if ( !file_exists( $loadFilePath ) )

                throw new SystemException(" config >>> {$file} file is not exists.");

            Facade::getInstance()->{$setContainerName} = Facade::getInstance()['Loader']->file( $loadFilePath , true );
        }

        $configArrayData = Facade::getInstance()->{$setContainerName};

        if( empty ( $configArrayData ) )

            return [];

        if( $conf )

            return Facade::getInstance()['ServicesParsing']->getConfig( $conf , $configArrayData );

        return $configArrayData;
    }
}