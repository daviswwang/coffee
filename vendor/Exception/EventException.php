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

namespace Coffee\Exception;

use services\interpret;
use Throwable;

/* @class EventException
 * @desc  异常处理捕捉
 * */
class EventException extends \Exception
{

    /* @func __construct
     * @desc 处理捕捉到的异常
     * */
    public function __construct ( $message = "" , $code = 0 , Throwable $previous = null )
    {
        parent::__construct ( interpret::msg ( $message ) , 600 + $code , $previous );
    }
}