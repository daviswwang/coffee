<?php

return [

    /*
     * debug 配置
     * @switch 是否开启调试 debug 模式
     * @message 错误提示 当 debug 为 false 时生效 如果未设置则抛出捕捉到的message
     * */
    'debug'=>[
        'switch'=>true,
        'message'=>'系统发生异常!'
    ],

    /* app 应用配置
     * @name 应用名称 此名称必须与应用根目录文件夹名一致
     * @mode 应用模式 可选模式 html/api/cli
     * @dir  应用可执行文件目录名->class存放目录名
     * @api  应用模式 api 下配置
     * --- format 输出格式
     * --- restriction_structure 输出结构体
     *     --- code_name 代码名称
     *     --- note_name 提示名称
     *     --- data_name 数据体名称
     * @default 应用执行必要的默认配置
     * @namespace 自定义注册命令空间
     * @autoload 自定义自动加载文件
     * */
    'app'=>[
        'name'=>'test',
        'mode'=>'api',
        'dir'=>'app',
        'api'=>[
            'format'=>'json',
            'structure'=>[
                'code_name'=>'code',
                'note_name'=>'note',
                'data_name'=>'data'
            ]
        ],
        'default'=>[
            'app_class'=>'index',
            'app_action'=>'index',
            '404_class'=>'_empty',
            '404_action'=>'index'
        ],
        'namespace'=>[
            
        ],
        'autoload'=>[
            
        ]
    ],

    /*
     * component 组件配置控制组件的使用
     * @middleware true 系统初始化则默认监听中间件模块 false 则反
     * @route true 系统初始化则默认监听路由匹配模块 false 则反
     * @firewall true 系统初始化启动防火墙模块 false 则反
     * @desc
     * */
    'component'=>[
        'middleware'=>false,
        'route'=>false
    ],
];