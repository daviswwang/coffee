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
}