<?php
/**
 * Created by PhpStorm.
 * User: denzyl
 * Date: 11-11-16
 * Time: 16:50
 */

namespace Av\HTTP;


use Av\Filter;

class Request
{
    public function getHeader($key)
    {
        return $_SERVER['HTTP_' + \ucfirst($key)];
    }

    public function getOrDefault(string $key, string $filter = null, $default = null)
    {
        if (isset($_GET[$key]) === false) return $default;

        $value = $_GET[$key];
        $value = $this->sanitize($filter, $value);
        return $value;
    }

    public function postOrDefault(string $key, string $filter = null, $default = null)
    {
        if (isset($_POST[$key]) === false) return $default;

        $value = $_POST[$key];
        $value = $this->sanitize($filter, $value);

        return $value;
    }

    /**
     * @param string $filter
     * @param $value
     * @return mixed
     */
    private function sanitize(string $filter, $value): mixed
    {
        if ($filter != null) {
            $filter = new Filter($value);
            $value = $filter->sanitize();
        }
        return $value;
    }

}