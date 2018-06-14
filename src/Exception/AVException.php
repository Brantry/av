<?php
namespace Av\Library\Exception;

use Exception;

class AVException extends Exception
{
    public function __construct(Exception $e)
    {
        /**
         * @todo implement logger
         */
        echo "<pre>";
        var_dump($e->getMessage());
        echo "</pre>";
    }
}