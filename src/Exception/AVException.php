<?php

namespace Av\Exception;

use Exception;

class AVException extends Exception
{
    public function __construct(Exception $e)
    {
        echo "<pre>";
        var_dump($e->getMessage());
        echo "</pre>";
    }
}