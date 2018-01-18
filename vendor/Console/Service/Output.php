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

    public function xml ( $note = 'success' , $code = 0 , $data = [] )
    {
        $node = simplexml_load_string( "<?xml version='1.0' encoding='utf-8'?><root />" );

        return $this->addDataToNode( $node , $this->restrictionStructure( $code , $note , $data ) )->asXML();
    }

    public function json ( $note = 'success' , $code = 0 , $data = [] )
    {
        return json_encode($this->restrictionStructure( $code , $note , $data ) );
    }


    private function addDataToNode( \SimpleXMLElement $node , $data )
    {
        foreach ($data as $key => $value) {

            if ( is_numeric( $key ) ) {

                $key = "unknownNode_". (string) $key;

            }

            $key = preg_replace( '/[^a-z0-9\-\_\.\:]/i' ,  '' , $key );

            if ( is_array( $value ) ) {

                $child = $node->addChild( $key );

                $this->addDataToNode( $child , $value );

            } else {

                $value = str_replace( '&' ,  '&amp;' , print_r( $value ,  true ) );

                $node->addChild( $key , $value );

            }
        }

        return $node;
    }


    private function restrictionStructure ( $code = 0 , $note = '', $data = [] )
    {
        if( !COFFEE_RUN_CLI )

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