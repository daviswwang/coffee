<?php

namespace coffee;

use \Whoops\Handler\XmlResponseHandler ;
use \Whoops\Handler\JsonResponseHandler;
use \Whoops\Handler\PrettyPageHandler;
use \Whoops\Handler\PlainTextHandler;
use \Whoops\Util\Misc;
use \services\config;

class abnormal
{
    public function listen()
    {
        //获取执行项目模式
        $mode = config::get('app.mode') ? : 'api';

        //判断执行模式是否合法
        if(!in_array($mode,['html','api','cli'])) $mode = 'api';

        //判断项目Debug是否启动
        if(config::get('debug.switch') === true)
        {
            //开启全部错误
            error_reporting(E_ALL);
            ini_set('display_errors',1);
        }

        //初始化错误机制组件
        return (new \Whoops\Run())->pushHandler($this->getHandle($mode))->register();
    }

    private function getHandle($mode = 'api')
    {
        switch ($mode)
        {
            case 'html':
                $handle = Misc::isAjaxRequest() ? new JsonResponseHandler() : new PrettyPageHandler();
                break;
            case 'cli':
                $handle = new PlainTextHandler();
                break;
            default:
                $handle = config::get('app.api.format') == 'xml' ? new XmlResponseHandler() : new JsonResponseHandler();
                break;
        }

        if(method_exists($handle,'addTraceToOutput'))
            $handle->addTraceToOutput(true);

        if(method_exists($handle,'setPageTitle'))
            $handle->setPageTitle('异常信息 - '.config::get('app.name'));

        return $handle;
    }
}