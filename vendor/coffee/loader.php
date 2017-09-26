<?php

namespace coffee;

use coffee\exception\interiorError;

class loader extends container
{
    public function fileHaveReturn($filePath = '')
    {
        if(!file_exists($filePath)) throw new interiorError("file path $filePath is no file.");

        return require $filePath;
    }
}