<?php

return [
    'default'=>[
        //队列最大长度
        'max_num'=>100000,

        //队列存活时间
        'expires'=>2592000,

        //缓存服务器->与cache配置项挂钩
        'servers'=>'default'
    ]
];