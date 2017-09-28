<?php

return [
    //设为 true 将开启调式模式
    'debug'=>false,

    //此处设置后 全局都将启用api格式输出 json or xml 支持的format 值有 api html cli
    'format'=>'html',

    //api format 输出格式  format 设置api 此设置才生效
    'api_output'=>'json',

    //约束一个访问规则 例如 api 项目
    'restriction'=>'/[:api]/[:version]/[:action]',

    //应用名称
    'app_name'=>'Test',

    //注册自定义命名空间
    'namespace'=>[
        "test\\"=>C_ITEM
    ],

    //是否启用中间件功能 默认 true 配置文件在 middleware进行配置
    'enable_middleware'=>true,

    //是否启用防火墙功能
    'enable_firewall'=>true,

    //约束一个输出结构体
    'restriction_structure'=>[
        'code_name'=>'code',
        'note_name'=>'note',
        'data_name'=>'data'
    ]
];