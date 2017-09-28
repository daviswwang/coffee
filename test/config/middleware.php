<?php

return [
    //得到请求后立刻执行中间件
    \services\middleware::BGR=>[

    ],

    //解析完路由立刻执行中间件
    \services\middleware::AGR=>[

    ],

    //路由得到的应用执行之前执行
    \services\middleware::BEA=>[

    ],

    //路由得到的应用执行之后执行
    \services\middleware::AEA=>[

    ],

    //触发NotFound之前执行
    \services\middleware::BNF=>[

    ],

    //触发NotFound之后执行
    \services\middleware::ANF=>[

    ]
];