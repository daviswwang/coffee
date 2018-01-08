<?php

namespace coffee;

class parsing
{
    public function namespace_to_class($str = '')
    {
        if(strstr($str,'\\'))
            return basename(str_replace('\\','/',$str));
        return basename($str);
    }

    public function object_to_array($object)
    {
        $arr = [];

        foreach ($object as $k=>$o) $arr[$k] = $o;

        return $arr;
    }
}