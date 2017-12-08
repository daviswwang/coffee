<?php

namespace component\template;

use coffee\container;
use coffee\exception\viewError;
use services\config;

class view extends container
{
    private $path = '',$assign = [] , $buffer_level;

    public function __construct()
    {
        $this->path = config::get('app.view.path') ? : C_ITEM.'view'.DIRECTORY_SEPARATOR;
        $this->buffer_level = ob_get_level();
    }


    public function assign(array $val = [])
    {
        $this->assign = array_merge($this->assign,$val);
    }

    public function display($file = '' , $return = false)
    {

        if(!is_file($this->path.$file.C_EXT))
        {
            throw new viewError($file.C_EXT." : template file is not found.");
        }

        extract($this->assign);

        ob_start();

        include($this->path.$file.C_EXT);

        if($return)
        {
            $buffer = ob_get_contents();
            @ob_end_clean();
            return $buffer;
        }

        if(ob_get_level() > $this->buffer_level + 1)
        {
            ob_end_flush();
        }

        //Todo... cache
        return false;
    }
}