<?php

namespace services;

use \Whoops\Handler\XmlResponseHandler ;
use \Whoops\Handler\JsonResponseHandler;
use \Whoops\Handler\PrettyPageHandler;
use \Whoops\Handler\PlainTextHandler;
use \Whoops\Util\Misc;

class abnormal
{
    public static function listen()
    {
        if(config::get('debug') !== true) return false;

        //开启全部错误
        error_reporting(E_ALL);
        ini_set('display_errors',1);

        //得到项目类型
        $format = config::get('format') ? : '';

        //实例化tips组件
        $whoops = new \Whoops\Run;

        //判断类型是否合法
        if(!in_array($format,['api','html','cli'])) return false;

        $handle = self::getHandle($format);
        $whoops->pushHandler($handle);
        $whoops->register();
        return true;
    }

    private static function getHandle($format = 'html')
    {
        if($format == 'api')
        {
            if(config::get('api_config.output') == 'xml')
                $handle = new XmlResponseHandler();
            else
                $handle = new JsonResponseHandler();
        }
        elseif($format == 'html')
        {
            if(Misc::isAjaxRequest())
                $handle = new JsonResponseHandler();
            else
                $handle = new PrettyPageHandler();
        }
        else
            $handle = new PlainTextHandler();

        if(method_exists($handle,'addTraceToOutput'))
            $handle->addTraceToOutput(true);

        if(method_exists($handle,'setPageTitle'))
            $handle->setPageTitle('异常信息 - '.config::get('app_name'));

        return $handle;
    }
}