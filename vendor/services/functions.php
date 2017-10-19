<?php

use coffee\container;

function di($object = NULL)
{
    if($object) return container::initialize()[$object]; else return container::initialize();
}

function raise($note = '' , $code = 0 , $data = [])
{
    if(\services\config::get('format') == 'html')
    {
        if(\Whoops\Util\Misc::isAjaxRequest())
            echo \services\output::json($note,$code,$data);
//        else
//            view()->assign()->display();
    }
    else
    {
        if(\services\config::get('api_config.output') == 'xml')
        {
            echo \services\output::xml($note,$code,$data);
        }
        else
        {
            echo \services\output::json($note,$code,$data);
        }
    }

    exit;
}