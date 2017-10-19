<?php

use services\middleware;

return [
    //设为 true 将开启调式模式
    'debug'=>true,

    //此处设置后 全局都将启用api格式输出 json or xml 支持的format 值有 api html cli
    'format'=>'api',

    //应用名称
    'app_name'=>'Test',

    //项目目录
    'app_directory'=>'app',
    
    //是否启用中间件功能 默认 true 配置文件在 middleware进行配置
    'middleware'=>true,

    //是否启用防火墙功能
    'firewall'=>true,

    //注册自定义命名空间
    'namespace'=>[
//        "test\\"=>C_ITEM
    ],

    //默认配置
    'default'=>[
        'class'=>'index',
        'action'=>'home',
        
        '404'=>[
            'view'=>'',
            'note'=>'404 file is not found.',
        ]
    ],

    //api 配置
    'api_config'=>[

        //约束输出格式
        'output'=>'json',

        //约束输出结构体
        'restriction_structure'=>[
            'code_name'=>'code',
            'note_name'=>'note',
            'data_name'=>'data'
        ]
    ],

    //防火墙配置
    'firewall_config'=>[
        1,2,3
    ],

    //中间件配置
    'middleware_config'=>[
        //得到请求后立刻执行中间件
        middleware::BGR=>[

        ],

        //解析完路由立刻执行中间件
        middleware::AGR=>[

        ],

        //路由得到的应用执行之前执行
        middleware::BEA=>[

        ],

        //路由得到的应用执行之后执行
        middleware::AEA=>[

        ],

        //触发NotFound之前执行
        middleware::BNF=>[

        ],

        //触发NotFound之后执行
        middleware::ANF=>[

        ]
    ],

];