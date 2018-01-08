<?php

return [
    //预计支持 websocket / http / tcp 目前仅支持websocket 模式
    'mode'=>'websocket',
    //监听地址 - 默认本机 0.0.0.0
    'address'=>'0.0.0.0',
    //监听端口 - 默认 9501
    'port'=>9502,
    //SSL证书 使用SSL模式必须
    'ssl_cert_file'=>'',
    //SSL密钥 使用SSL模式必须
    'ssl_key_file'=>'',
    //线程数
    'threads_num'=>16,
    //进程数 一般设置 CPU(核心)的 1~4倍，具体按照系统负载调节
    'process_num'=>8,
    //最大连接数
    'connect_num'=>10000,
    //最大请求执行次数 执行完次数自动重启 0表示 不重启
    'request_num'=>0,
    //监听队列长度
    'listen_num'=>128,
    //是否作为守护进行执行
    'is_daemon'=>false,
    //CPU亲和设置
    'cpu_affinity'=>true,
    //是否启用tcp_nodelay
    'tcp_nodelay'=>true,
    //此参数设定一个秒数，当客户端连接连接到服务器时，在约定秒数内并不会触发accept，直到有数据发送，或者超时时才会触发。
    'tcp_defer_accept'=>5,
    //指定错误日志文件。在运行期发生的异常信息会记录到这个文件中。默认会打印到屏幕。
    'error_log_file'=>'/var/log/socket_error.log',
    //EOF检测 用于检测数据是否完整，如果不完整会继续等待新的数据到来。直到收到完整的一个请求
    'eof_check'=>false,
    //设置EOF
    'package_eof'=>"\r\n\r\n",
    //心跳检测 每隔多少秒检测一次，单位秒，轮询所有TCP连接，将超过心跳时间的连接关闭掉
    'heartbeat_check_interval'=>600,
    //TCP连接的最大闲置时间，单位s , 如果某fd最后一次发包距离现在的时间超过
    'heartbeat_idle_time'=>600,
    //1平均分配，2按FD取模固定分配，3抢占式分配，默认为取模(dispatch=2)
    'dispatch_mode'=>2
];