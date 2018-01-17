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

class Output
{

    public function errorStructural ( $code = 0 , $note = '', $data = [] )
    {
        if ( Facade::getInstance()['ServicesConfig']->get ( 'debug.switch' ) )

            return $this->restrictionStructure(
                $code ,
                $note ,
                $data
            );
        
        else

            return $this->restrictionStructure(
                $code ,
                Facade::getInstance()['ServicesConfig']->get ( 'debug.message' ) ? : $note ,
                []
            );

    }


    private function restrictionStructure ( $code = 0 , $note = '', $data = [] )
    {
        if( COFFEE_RUN_MODE == 'web' )

            ob_clean();

        $restrictionStructure = Facade::getInstance()['ServicesConfig']->get ( 'app.api.structure' )
            ? : [ 'code_name' => 'code' , 'note_name' => 'note' , 'data_name' => 'data' ];

        return [
            $restrictionStructure['code_name'] => $code ,
            $restrictionStructure['note_name'] => Facade::getInstance()['ServicesInterpret']->msg ( $note ) ,
            $restrictionStructure['data_name'] => $data
        ];
    }
}