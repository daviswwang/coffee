<?php

namespace coffee;

use coffee\exception\routeError;
use services\config;

class route
{
    private $config   = [];
    
    public function listen()
    {
        //中间件操作 得到路由之前操作
        di('middleware')->callback('BEFORE_GET_ROUTE');

        //获取路由配置
        $this->config = config::get('','route');

        //验证路由执行规则
        $this->check_is_match_route();
    }

    private function check_is_match_route()
    {
        if(empty($this->config) || config::get('component.route') === false)
            $params = $this->parse_path_info(di('request')->get_path_info());
        else
        {
            //是否安装Route组件
            if(!class_exists("\\component\\route\\route"))
                throw new routeError("please install route component.");

            //得到解析后path_info并开始解析path_info
            $params = $this->parse_path_info(
                (new \component\route\route($this->config))->match(
                    di('request')->get_path_info()
                )
            );
        }
        
        //解析结果注入请求实例
        di('reflection')->object(di('request'))->setPrivateAttribute('service',$params);

        //中间件操作 得到路由之后操作
        di('middleware')->callback('AFTER_GET_ROUTE');
    }

    private function parse_path_info($path_info = '')
    {
        $namespace = $old_namespace = "\\".(config::get('app.name'))."\\".(config::get('app.dir'))."\\";
        $status = 200;

        if(empty($path_info) || $path_info == '/')
        {
            $class  = config::get('app.default.app_class');
            $action = config::get('app.default.app_action');
        }
        else
        {
            $path   = explode('/',trim($path_info,'/'));

            $file_path = C_ITEM.config::get('app.dir').DIRECTORY_SEPARATOR;

            if(count($path) == 1)
            {
                if(file_exists($file_path.$path[0].C_EXT))
                {
                    $class  = $path[0];
                    $action = config::get('app.default.app_action');
                }
            }
            else
            {
                $class = $action = '';

                foreach ($path as $k=>$v)
                {
                    if(is_dir($file_path.$v))
                    {
                        $namespace .= $v."\\";
                        if(($k+1) == count($path))
                        {
                            $class  = config::get('app.default.app_class');
                            $action = config::get('app.default.app_action');
                        }

                        $file_path .= $v.DIRECTORY_SEPARATOR;
                    }
                    elseif(file_exists($file_path.$v.C_EXT))
                    {
                        $class = $v;
                        if(($k+1) == count($path))
                        {
                            $action = config::get('app.default.app_action');
                        }
                    }
                    elseif(($k+1) == count($path) && !empty($class))
                    {
                        $action = $v;
                    }
                }
            }
        }

        //组装
        if(empty($class) && empty($action))
        {
            $class = config::get('app.default.404_class');
            $action= config::get('app.default.404_action');
            //Not Found 触发之前执行
            di('middleware')->callback('BEFORE_NOT_FOUND');
            $status = 404;
            $namespace = $old_namespace;
        }

        return [
            'namespace'=>$namespace,
            'application'=>$namespace.$class,
            'class'=>$class,
            'action'=>$action,
            'status'=>$status
        ];
    }


}