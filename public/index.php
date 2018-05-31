<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

require_once '../vendor/autoload.php';

$requestUri = ltrim($_SERVER['REQUEST_URI'], '/');

if (empty($requestUri)) {
    (new \Yar_Server(new \App\Server\Error('Request Uri invalid')))->handle();
} else {
    list($class, $function) = explode('.', $requestUri);
    $server = new \Yar_Server(new \App\Bootstrap\Service($class, $function));
    $server->handle();
}
