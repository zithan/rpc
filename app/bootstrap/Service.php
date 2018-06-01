<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Bootstrap;

class Service
{
    private $class;
    private $function;

    public function __construct($class, $function)
    {
        $this->class = $class;
        $this->function = $function;
    }

    public function run(array $params = [])
    {
        //$params = json_decode($params, true);
        return $this->get_obj($this->class, $this->function, $params);
    }

    function get_obj($class, $function, $params)
    {
        $class = '\App\Server\\' . $class;

        if (! class_exists($class)) {
            return 'class not found...';
        }

        if (! method_exists($class, $function)) {
            return 'method not found...';
        }

        $class = new \ReflectionClass($class);
        return call_user_func_array(array($class->newInstance(), $function), $params);
    }
}