<?php

namespace component\socket;

use services\config;
use services\request;

class websocket
{
    private $conf = [];
    
    private $conn = NULL;
    
    public function __construct()
    {
        $this->conf = config::get('','socket');
        $this->conn = new \swoole_websocket_server($this->conf['address'],$this->conf['port']);
    }

    public function listen()
    {
        $this->_log("socket listen start...");
        $this->_socket_set();
        $this->conn->on('start',[$this,'_bind_start']);
        $this->conn->on('open',[$this,'_bind_open']);
        $this->conn->on('message',[$this,'_bind_message']);
        $this->conn->on('close',[$this,'_bind_close']);
        $this->conn->start();
    }

    private function _socket_set()
    {
        $this->_log("socket set working config...");
        $this->conn->set([
            'max_conn'                  =>$this->conf['connect_num'],
            'daemonize'                 =>$this->conf['is_daemon']?1:0,
            'reactor_num'               =>$this->conf['threads_num'],
            'worker_num'                =>$this->conf['process_num'],
            'max_request'               =>$this->conf['request_num'],
            'backlog'                   =>$this->conf['listen_num'],
            'open_cpu_affinity'         =>$this->conf['cpu_affinity']?1:0,
            'open_tcp_nodelay'          =>$this->conf['tcp_nodelay']?1:0,
            'tcp_defer_accept'          =>$this->conf['tcp_defer_accept'],
            'log_file'                  =>$this->conf['error_log_file'],
            'open_eof_check'            =>$this->conf['eof_check'],
            'package_eof'               =>$this->conf['package_eof'],
            'heartbeat_check_interval'  =>$this->conf['heartbeat_check_interval'],
            'heartbeat_idle_time'       =>$this->conf['heartbeat_idle_time'],
            'dispatch_mode'             =>$this->conf['dispatch_mode'],
        ]);
    }

    public function _bind_start()
    {
        $this->_log("socket application startup....");
    }

    public function _bind_open( $socket , $request )
    {
        $this->_log("socket connect success by fd : {$request->fd}...");

        $socket->push($request->fd,raise('connect successful.'));
    }

    public function _bind_message( $socket , $frame )
    {
        $this->_log('-----------------------------');
        $this->_log("receive client message successful by fd : {$frame->fd}.");

        $result         = json_decode($frame->data,true);
        $result['fd']   = $frame->fd;

        if(isset($result['server'])) foreach ($result['server'] as $k=>$v) $_SERVER[strtoupper($k)] = $v;
        
        if(isset($result['header'])) foreach ($result['header'] as $k=>$v) $_SERVER[strtoupper('HTTP_'.$k)] = $v;

        $this->_log("server & header setting is ok by fd : {$frame->fd}.");

        if(WEBSOCKET_STATUS_FRAME != 3)
        {
            $msg = "socket connect abnormal by fd : {$frame->fd}...";
            $this->_log($msg);
            raise($msg,10000);
            $socket->push($frame->fd,di('response')->raise());
            $this->conn->close($frame->fd);
            return false;
        }
        
        $this->_log("socket connect normal by fd : {$frame->fd}.");

        //中间件初始化机制
        (new \coffee\middleware)->listen();

        $this->_log("application middleware init is ok by fd : {$frame->fd}.");

        //初始化路由模块
        (new \coffee\route)->listen();

        $this->_log("application route listen is ok by fd : {$frame->fd}.");

        if(!isset($_SERVER['REQUEST_METHOD']))
        {
            $msg = 'connect abnormal,please reconnect...';
            $this->_log($msg);
            raise($msg,999);
            $socket->push($frame->fd,di('response')->raise());
            $this->conn->close($frame->fd);
            return false;
        }

        $this->_log("server request method is ok by fd : {$frame->fd}.");

        if(request::get_server('request_method') == 'POST')
            $_POST = $result['data'];
        else
            $_GET  = $result['data'];

        if($error = di('response')->raise())
        {
            $this->_log("response error by fd : {$frame->fd}.");
            $socket->push($frame->fd,$error);
        }
        else
        {
            $this->_log("push message to client by fd : {$frame->fd}.");
            $response = di('response')->listen()->run()->send(true);

            if(is_array($response) && isset($response['fds']))
            {
                foreach ($response['fds'] as $f)
                    if($socket->exist($f))
                        if(isset($response['res']))
                            $socket->push($f,raise('successful.',0,$response['res']));
                        else
                            $socket->push($f,raise('successful.'));
            }
            elseif(empty($response))
            {
                $socket->push($frame->fd,raise('successful.'));
            }
            else
            {
                $socket->push($frame->fd,raise('successful.',0,$response));
            }
        }

        di()->destruction();

        return true;

    }

    public function _bind_close( $socket , $fd )
    {
        $this->_log("socket connection close... for fd : $fd");
    }

    private function _log($msg)
    {
        echo "[log]: $msg\n";
    }
}