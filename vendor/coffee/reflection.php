<?php

namespace coffee;

use coffee\exception\systemError , services\parse;

class reflection
{

    private $reflection = NULL;

    private $setObject  = NULL;

    public function object($obj = NULL)
    {
        if(!is_object($obj)) throw new systemError('reflection set object is not valid object.');

        $this->setObject  = $obj;

        $this->reflection = new \ReflectionClass($this->setObject);

        return $this;
    }

    public function setPrivateAttribute($key = '' , $val = NULL)
    {
        if(!$this->reflection->hasProperty($key))
            throw new systemError('attribute is undefined at '.$key);

        $attribute = $this->reflection->getProperty($key);

        if(!$attribute->isPrivate())
            throw new systemError('this attribute is not private at '.$key);

        try
        {
            $attribute->setAccessible(true);
            $attribute->setValue($this->setObject,$val);
            di()->set(di('parsing')->namespace_to_class($this->reflection->getName()),$this->setObject);
        }
        catch (\ReflectionException $e)
        {
            throw new systemError($e->getMessage());
        }
    }


}