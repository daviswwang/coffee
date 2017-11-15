<?php

use coffee\container , services\config;

function di($object = NULL)
{
    if($object) return container::initialize()[$object]; else return container::initialize();
}

function raise($note = '' , $code = 0 , $data = [])
{
    if(config::get('app.mode') == 'html')
    {
        if(\services\input::is_ajax())
            echo di('output')->json($note , $code , $data);
        else
            echo 'does not support html.';
    }
    else
    {
        if(config::get('app.api.output') == 'xml')
        {
            echo di('output')->xml($note , $code , $data);
        }
        else
        {
            echo di('output')->json($note , $code , $data);
        }
    }

    if($code > 99 && $code < 600) header('Status:'.$code);

    exit;
}



