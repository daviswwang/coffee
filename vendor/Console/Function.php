<?php

if ( ! function_exists ( 'raise' ) )
{
    function raise ( $note = '' , $code = 0 , $data = [] )
    {
        if ( COFFEE_APP_MODE == 'html' )
        {
            if ( \Coffee\Console\Structural\Facade::getInstance()['ServicesInput']->isAjax() )
            {
                $result = \Coffee\Console\Structural\Facade::getInstance()['ServicesOutput']->json(

                    $note , $code , $data
                );
            }
            else
            {
                $result = 'does not support html.';
            }
        }
        elseif( COFFEE_APP_MODE == 'api' )
        {
            if( \Coffee\Console\Structural\Facade::getInstance()['ServicesConfig']->get( 'app.api.format' ) == 'xml' )
            {
                $result = \Coffee\Console\Structural\Facade::getInstance()['ServicesOutput']->xml(

                    $note , $code , $data
                );
            }
            else
            {
                $result = \Coffee\Console\Structural\Facade::getInstance()['ServicesOutput']->json(

                    $note , $code , $data
                );
            }
        }
        else
        {
            $result = "
                CODE:$code\n
                NOTE:$note\n
            ".(is_array($data) ? implode("\n",$data) : $data) . "\n";
        }
        
        echo $result; exit();
    }
}