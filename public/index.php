<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

require_once '../vendor/autoload.php';

$action = $_GET['at'];

if (! get_obj($action)) {
    // 如果初始化异常
}

function get_obj($obj_name)
{
    $obj_name = '\Rpc\Servers\\' . $obj_name;
    if (! class_exists($obj_name)) {
        return false;
    }
    $class = new \ReflectionClass($obj_name);
    return $class->newInstance();
}