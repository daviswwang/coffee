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

use Coffee\Console\Frame\Middleware;
use Coffee\Console\Structural\Facade;

class Response
{

    private $applicationInstance = NULL;

    private $applicationClass    = NULL;

    private $applicationAction   = NULL;

    private $applicationResult   = NULL;

    public function run()
    {
        Middleware::executeCallback('BEFORE_EXEC_APP');

        $application = Facade::getInstance()['ServicesRequest']->getApplication();

        $this->applicationInstance = new  $application();

        $this->applicationClass    = Facade::getInstance()['ServicesRequest']->getClass();

        $this->applicationAction   = Facade::getInstance()['ServicesRequest']->getAction();

        if( !method_exists( $this->applicationInstance , $this->applicationAction ) )
        {
            $application = Facade::getInstance()['ServicesRequest']->getInitNamespace() .
                           Facade::getInstance()['ServicesConfig']->get ( 'app.default.404_class' );

            $this->applicationInstance = new $application();

            $this->applicationClass    = Facade::getInstance()['ServicesConfig']->get ( 'app.default.404_class' );

            $this->applicationAction   = Facade::getInstance()['ServicesConfig']->get ( 'app.default.404_action' );
        }

        $this->applicationResult = call_user_func( [ $this->applicationInstance , $this->applicationAction ] );

        Middleware::executeCallback('AFTER_EXEC_APP');
        
        return $this;
    }

    public function send()
    {
        if( COFFEE_RUN_MODE == 'web' )
        {
            if ( COFFEE_APP_MODE == 'api' )
            {
                raise( 'successful.' , 0 , $this->applicationResult );
            }
            elseif ( COFFEE_APP_MODE == 'html')
            {
                if ( Facade::getInstance()['ServicesInput']->isAjax() )
                {
                    raise( 'successful.' , 0 , $this->applicationResult );
                }
                elseif (

                    Facade::getInstance()['ServicesConfig']->get( 'app.view.autoload' ) &&
                    $this->applicationClass != Facade::getInstance()['ServicesConfig']->get( 'app.default.404_class' )

                )
            }
        }
        else
        {

        }
        if(config::get('app.mode') == 'api')
            raise('success',0,$this->result);
        elseif(
            config::get('app.mode') == 'html' &&
            config::get('app.view.autoload') &&
            !input::is_ajax() &&
            r::get_class() != config::get('app.default.404_class') &&
            method_exists($this->obj,r::get_action())
        )
            view::display(r::get_class().'/'.r::get_action());
    }
}