<?php

/* +-------------------------------------------------------
 * | Coffee PHP Framework Version 2.0.0                   +
 * +-------------------------------------------------------
 * | Copyright (c) 2017 Coffee All rights reserved.       +
 * +-------------------------------------------------------
 * | Git Link ( https://github.com/naofbear/coffee )      +
 * +-------------------------------------------------------
 * | Author : huakaiquan Email : <huakaiquan@qq.com>      +
 * +-------------------------------------------------------
 * | Licensed(http://www.apache.org/licenses/LICENSE-2.0) +
 * +-------------------------------------------------------
 * | 拒绝臃肿、快速开发、简单上手、即为 Coffee 初心              +
 * | Welcome to join us to make it easier and easier.     +
 * +-------------------------------------------------------
 * */

namespace Coffee\Console\Structural;

use Coffee\Exception\SystemException;

/* @class StructuralContainer
 * @desc  容器服务类
 * @job   存储已实例化类
 * */
class Container implements \ArrayAccess
{
     /* @attribute $_ServicePool
      * @desc 容器对象池,存储所有对象
      * @type <Array>
      * */
     private $_ServicePool = [];

     /* @attribute $_ServiceHit
      * @desc 服务命中次数
      * @type <Array>
      * */
     private $_ServiceHit  = [];

     /* @attribute $instance
      * $desc 容器对象
      * @type <Const Default Null>
      * */
     private static $instance = NULL;

     /* @func getInstance
      * @desc 获取容器对象
      * @return <Object>
      * */
     public static function getInstance()
     {
         if(NULL === self::$instance)

             self::$instance = new self();

         return self::$instance;
     }

     public function __construct ()
     {
         $AutoloadConsoleClassMap = [
            'ServicesParsing'   => "\\Coffee\\Console\\Service\\Parsing",
            'ServicesInterpret' => "\\Coffee\\Console\\Service\\Interpret",
            'ServicesConfig'    => "\\Coffee\\Console\\Service\\Config",
            'ServicesOutput'    => "\\Coffee\\Console\\Service\\Output",
            'ServicesRequest'   => "\\Coffee\\Console\\Http\\Request",
            'ServicesResponse'  => "\\Coffee\\Console\\Http\\Response",
            'ServicesView'      => "\\Coffee\\Console\\Http\\View",
            'Loader'            => "\\Coffee\\Console\\Frame\\Loader",
            'Reflection'        => "\\Coffee\\Console\\Structural\\Reflection",
         ];
         
         array_walk ( $AutoloadConsoleClassMap , function ( $val , $set ) { $this->_injection ( $set , $val ); } );
     }

     /* @func __call
      * @desc 魔术方法 __call 以动态方式将对象注入容器
      * @desc 魔术方法 __call 以动态方式从容器获取指定对象
      * @return [ boolean ( true | false ) | <Object> ]
      * */
     public function __call ( $name , $arguments )
     {
         switch( strtolower( substr( $name , 0 , 3) ) )
         {
             case 'set':

                 return $this->_injection ( substr ( $name , 3 ) , $arguments[0] ? : NULL );
             case 'get':

                 return $this->_obtain ( substr( $name , 3 ) );
         }

         throw new SystemException("Call to undefined method container::$name");
     }

     /* @func __set
      * @desc 魔术方法 __set 以赋值方式注入对象
      * @return boolean ( true | false
      * */
     public function __set ( $set , $val )
     {
         return $this->_injection ( $set , $val );
     }

     /* @func __get
      * @desc 魔术方法 __get 以获取成员变量方式从容器中获取指定对象
      * @return <Object>
      * */
     public function __get ( $set )
     {
         return $this->_obtain ( $set );
     }

    /* @func offsetSet
     * @desc 以数组模式注入容器
     * @param $set <容器存储的对象名称>
     * @param $val <容器存在的对象>
     * @return boolean ( true | false )
     * */
    public function offsetSet ( $set , $val )
    {
        return $this->_injection ( $set , $val );
    }

    /* @func offsetGet
     * @desc 以数组模式获取容器类已实例化指定对象
     * @param $set <容器存储的对象名称>
     * @return <Object>
     * */
    public function offsetGet ( $set )
    {
        return $this->_obtain ( $set );
    }

    /* @func offsetExists
     * @desc 检测容器中是否已实例化指定对象
     * @param $set <容器存储的对象名称>
     * @return boolean ( true | false )
     * */
    public function offsetExists ( $set )
    {
        return isset ( $this->_ServicePool[$set] );
    }

    /* @func offsetUnset
     * @desc 销毁已存在容器中的指定对象
     * @param $set <容器存储的对象名称>
     * @return boolean ( true | false )
     * */
    public function offsetUnset ( $set )
    {
        if ( $this->offsetExists( $set ) )
        {
            unset ( $this->_ServicePool[$set] );

            return true;
        }

        return false;
    }

    /* @func _injection
     * @desc 将指定类生成对象注入容器中
     * @param $set <容器存储的对象名称>
     * @param $val <容器存在的对象>
     * @return boolean ( true | false )
     * */
    private function _injection ( $set , $val )
    {
        $this->_hitReset( $set );

        if ( !$val ) return false;

        if ( $val instanceof \Closure )

            $val = $val();

        elseif ( is_string( $val ) && class_exists( $val ) )

            $val = new $val();

        $this->_ServicePool[ $set ] = serialize ( $val );

        return true;
    }

    /* @func _obtain
     * @desc 根据名称从容器中获取指定的对象
     * @param $set <容器存储的对象名称>
     * @return <Object>
     **/
    private function _obtain ( $set )
    {
        
        $this->_hitAdd( $set );

        if( $this->offsetExists ( $set ) )

            return unserialize ( $this->_ServicePool[$set] );

        throw new SystemException ( "this {$set} object does not initialize." );
    }

    /* @func _hitReset
     * @desc 根据服务名称重设服务命中次数
     * @param $set <容器存储的对象名称>
     * */
    private function _hitReset ( $set )
    {
        $this->_ServiceHit[$set] = 0;
    }

    /* @func _hitAdd
     * @desc 根据服务名称追加服务命中次数
     * @param $set <容器存储的对象名称>
     * */
    private function _hitAdd ( $set )
    {
        if( isset( $this->_ServiceHit[$set] ))

            $this->_ServiceHit[$set] += 1;
        else

            $this->_ServiceHit[$set]  = 1;
    }
}