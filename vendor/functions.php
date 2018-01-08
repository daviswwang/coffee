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
            $res = di('output')->json($note , $code , $data);
        else
            $res = 'does not support html.';
    }
    else
    {
        if(config::get('app.api.output') == 'xml')
        {
            $res = di('output')->xml($note , $code , $data);
        }
        else
        {
            $res = di('output')->json($note , $code , $data);
        }
    }

    if(defined('RUN_MODE'))
    {
        if($code && $res)
            di('reflection')->object(di('response'))->setPrivateAttribute('result',$res);
        return $res;
    }
    else
    {
        if($code > 99 && $code < 600) header('Status:'.$code);

        echo $res;

        exit;
    }

}

function win32Raise($note = '' , $code = 0 , $data = [])
{
    echo di('output')->json($note , $code , array($data), 256);
    if($code > 99 && $code < 600) header('Status:'.$code);
    exit;
}



