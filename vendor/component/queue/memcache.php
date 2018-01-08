<?php

namespace component\queue;

use coffee\exception\cacheError;
use services\cache;

class memcache
{
    /*
     * @desc save cache connect link.
     * @default NULL
     * @注释 存储指定缓存服务器的链接
     * **/
    private $cache = NULL;

    /*
     * @desc save queue length
     * @attribute KEY
     * @注释 存储缓存队列的长度
     * */
    private $length = '_QUEUE_LENGTH';

    /*
     * @desc save queue expire
     * @attribute KEY
     * @注释 存储缓存队列的存活时间
     * */
    private $expire = '_QUEUE_EXPIRE';

    /*
     * @desc save queue left top offset num
     * @attribute KEY
     * @注释 存储缓存队列左侧初始值
     * */
    private $_l_top = '_QUEUE_L_TOP';

    /*
     * @desc save queue left end offset num
     * @attribute KEY
     * @注释 存储缓存队列左侧末尾值
     * */
    private $_l_end = '_QUEUE_L_END';

    /*
     * @desc save queue right top offset num
     * @attribute KEY
     * @注释 存储缓存队列右侧初始值
     * */
    private $_r_top = '_QUEUE_R_TOP';

    /*
     * @desc save queue right end offset num
     * @attribute KEY
     * @注释 存储缓存队列右侧末尾值
     * */
    private $_r_end = '_QUEUE_R_END';

    /*
     * @desc save queue exec error
     * @注释 存储执行过程错误
     * */
    private $_error = '' ;

    /*
     * @desc save queue max num
     * @注释 存储缓存队列允许插入的最大值
     * */
    private $_conf_maxnum = 0 ;

    /*
     * @desc save queue expire
     * @注释 存储缓存队列存活时间
     * */
    private $_conf_expire = 0 ;

    /*
     * @desc construct
     * @param $conf is array 队列配置
     * @return not return
     * */
    public function __construct( array $conf )
    {
        $this->cache = cache::connect($conf['servers']);
        $this->_conf_maxnum = $conf['max_num'];
        $this->_conf_expire = $conf['expires'];
    }

    /*
     * @desc lpush write data for queue left
     * @注释 为左侧的队列写入数据
     * @param $name queue name 队列名称
     * @param $val 写入队列的值
     * @return boolean|(true|false)
     * */
    public function lpush( $name , $val = '')
    {
        return $this->_push( $name , $val , true );
    }

    /*
     * @desc rpush write data for queue right
     * @注释 为右侧的队列写入数据
     * @param $name queue name 队列名称
     * @param $val 写入队列的值
     * */
    public function rpush( $name , $val = '')
    {
        return $this->_push( $name , $val , false );
    }

    /*
     * @desc lpop get data by queue left
     * @注释 基于队列左侧弹出数据
     * @param $name 缓存队列名称
     * */
    public function lpop( $name )
    {
        return $this->_pop( $name , true );
    }

    /*
     * @desc rpop get data by queue right
     * @注释 基于队列右侧弹出数据
     * @param $name 队列名称
     * */
    public function rpop( $name )
    {
        return $this->_pop( $name ,false );
    }

    /*
     * @desc get queue data length
     * @注释 获取当前队列长度（即队列数量）
     * @param $name 队列名称
     * */
    public function length($name)
    {
        $length = $this->cache->get($name.$this->length);

        return ($length > 0  ? $length : 0);
    }

    /*
     * @func function error
     * */
    public function error()
    {
        return $this->_error;
    }

    /*
     * @desc delete this queue
     * @注释 删除此队列,即清空队列
     * @param $name 队列名称
     * */
    public function delete( $name )
    {
        try
        {
            //清除数据
            if(!$this->cache->deleteMulti($this->_get_all_keys( $name )))
                throw new cacheError('multi delete failure.');

            //清除偏移
            if(
                !$this->cache->delete( $name.$this->_l_top ) ||
                !$this->cache->delete( $name.$this->_l_end ) ||
                !$this->cache->delete( $name.$this->_r_top ) ||
                !$this->cache->delete( $name.$this->_r_end )
            )
                throw new cacheError('delete offset failure.');

            //清除存活时间
            if( !$this->cache->delete( $name.$this->expire ) )
                throw new cacheError('delete expire failure.');

            //清除队列长度
            if( !$this->cache->delete( $name.$this->length ) )
                throw new cacheError('delete length failure.');

        }
        catch (cacheError $e)
        {
            $this->_error = $e->getMessage();
            return false;
        }

        return true;
    }

    /*
     * @func function _pop
     * @desc get data by queue & flag | left with right
     * @param $name queue name
     * @param $flag queue data offset
     * @return string
     * */
    private function _pop($name , $flag)
    {
        try
        {
            //get top num by queue
            $top_num = $this->_get_top_num($name , $flag);

            //get data by queue
            $cache = $this->cache->get( $name.( $flag ? '_l_' : '_r_' ).$top_num );

            //this offset + 1
            if( !$this->_set_top_num( $name , $top_num + 1 , $flag ) )
                throw new cacheError('set left top num failure.');

            //queue length offset - 1
            if( !$this->_dec_queue_length( $name ) )
                throw new cacheError('decrement length failure.');

            //data destruct
            if( !$this->cache->delete( $name.($flag ? '_l_' : '_r_' ).$top_num ) )
                throw new cacheError('destruction queue data failure.');

            return $cache;
        }
        catch (cacheError $e)
        {
            $this->_error = $e->getMessage();
            return false;
        }
    }

    /*
     * @func function _push
     * @desc push data for queue & flag | left with right
     * @param $name queue name
     * @param $val write data
     * @param $flag queue data offset
     * @return boolean | (true | false)
     * */
    private function _push( $name , $val , $flag )
    {
        try
        {
            //is overflow max num
            if( $this->length( $name ) >= $this->_conf_maxnum )
                throw new cacheError('queue overflow,please look max num setting.');

            //get queue end num
            $end_num = $this->_get_end_num($name,$flag);

            //if left offset is 0 & this is start... set top offset
            if( $end_num === 0 && !$this->_set_top_num( $name , $end_num , $flag ))
                throw new cacheError('set left top num failure.');

            //write queue data and flag & left with right
            if( !$this->cache->set($name.( $flag ? '_l_' : '_r_' ).$end_num, $val , $this->_get_surplus_expire( $name ) ) )
                throw new cacheError('write queue failure by '.$name);

            //increment queue length
            if( !$this->_inc_queue_length( $name ) )
                throw new cacheError('increment length failure.');

            //update queue end num and offset + 1
            if( !$this->_set_end_num( $name ,$end_num + 1 , $flag ) )
                throw new cacheError('set left end num failure.');
        }
        catch ( cacheError $e )
        {
            $this->_error = $e->getMessage();
            return false;
        }

        return true;
    }

    /*@func function _get_all_keys
     *@param $name queue name
     *@return arr
     * */
    private function _get_all_keys( $name )
    {
        //define arr
        $keys = [];

        //get left keys
        for($i = $this->_get_top_num( $name , true ) ; $i <= $this->_get_end_num( $name , true ) ; $i++ )
            array_push( $keys , $name .'_l_'. $i );

        //get right keys
        for($i = $this->_get_top_num( $name , false ) ; $i <= $this->_get_end_num( $name , false ) ; $i++ )
            array_push( $keys , $name .'_r_'. $i );

        return $keys;
    }

    /*
     * @func function _get_surplus_expire
     * @param $name queue name
     * $return int
     * */
    private function _get_surplus_expire( $name )
    {
        $expire = $this->cache->get( $name.$this->expire );

        if( !is_int( $expire ) )
        {
            $expire = $this->_conf_expire + time();
            $this->cache->set( $name.$this->expire , $expire , $this->_conf_expire );
        }

        return ( $expire - time() );
    }

    /*
     * @func function _inc_queue_length
     * @param $name queue name
     * @param $num inc num
     * @return boolean | (true | false)
     * */
    private function _inc_queue_length( $name , $num = 1 )
    {
        if( !$length = $this->length( $name ) )
            $this->cache->set( $name , 0 , $this->_get_surplus_expire( $name ) );
        
        return $this->cache->set( $name.$this->length , $length + $num , $this->_get_surplus_expire( $name ) );
    }

    /*
     * @func function _dec_queue_length
     * @param $name queue name
     * @param $num dec num
     * @return boolean | (true | false)
     * */
    private function _dec_queue_length( $name , $num = 1 )
    {
        if( !$length = $this->length( $name ) )
            $this->cache->set( $name , 0 , $this->_get_surplus_expire( $name ) );

        return $this->cache->set( $name.$this->length , $length - $num , $this->_get_surplus_expire( $name ) );
    }

    /*
     * @func function _get_end_num
     * @param $name queue name
     * $param $flag | (true | false)
     * @return int
     * */
    private function _get_end_num( $name , $flag )
    {
        $end_num = $this->cache->get( $name.( $flag ? $this->_l_end : $this->_r_end ) );

        if( !is_int( $end_num ) )
        {
            $this->_set_end_num( $name , 0 , $flag );
            $end_num = 0;
        }
        
        return $end_num;
    }

    /*
     * @func function _set_end_num
     * @param $name queue name
     * @param $num offset int
     * @param $flag | (true | false)
     * @return boolean | (true | false)
     * */
    private function _set_end_num($name , $num , $flag)
    {
        return $this->cache->set( $name.( $flag ? $this->_l_end : $this->_r_end ), $num  , $this->_get_surplus_expire( $name ) );
    }

    /*
     * @func function _get_top_num
     * @param $name queue name
     * @flag | (true | false)
     * @return int
     * */
    private function _get_top_num( $name , $flag )
    {
        $top_num = $this->cache->get($name.( $flag ? $this->_l_top : $this->_r_top ) );

        if( !is_int( $top_num ) )
        {
            $this->_set_top_num( $name , 0 , $flag );
            $top_num = 0;
        }

        return $top_num;
    }

    /*
     * @func function _set_top_num
     * @param $name queue name
     * @param $num offset int
     * @param $flag | (true | false)
     * @return boolean | (true | false)
     * */
    private function _set_top_num($name , $num , $flag)
    {
        return $this->cache->set($name.( $flag ? $this->_l_top : $this->_r_top ), $num , $this->_get_surplus_expire( $name ) );
    }
}