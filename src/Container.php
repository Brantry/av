<?php

namespace Av;
/**
 * Class Container
 * @package Av
 */
class Container
{
    private static $container;

    public static function DI()
    {
        return self::$container;
    }

    public static function set($key, $function)
    {
        self::$container[$key] = $function;
    }

    public static function get($key)
    {
        return self::$container[$key];
    }
}
