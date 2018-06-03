<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

define('APP_PATH', realpath(dirname(__FILE__) . '/../'));

require_once  APP_PATH . '/vendor/autoload.php';
require_once APP_PATH . '/app/common/helper.php';

use DI\ContainerBuilder;
use function DI\create;
use function DI\get;
use Relay\Relay;

use App\Bootstrap\Service;
use App\Server\Error as ErrorController;

function exception_error_handler($severity, $message, $file, $line)
 {
     //var_dump($severity);
     if (!(error_reporting() & $severity)) {
         return;
     }
     // @todo 记录使用异常日志
     throw new \Exception($message);
 }

 set_error_handler("exception_error_handler", E_NOTICE | E_WARNING);

try {
    error_log(time().':'.'send req success'.PHP_EOL, 3, APP_PATH . '/log/' . date('Ymd') . '.log');

    $requestUri = strrchr($_SERVER['REQUEST_URI'], '/');
    if (!$requestUri) {
        // @todo 不合法的路径
    }
    $requestUri = ltrim($requestUri, '/');
    if (!strpos($requestUri, '.')) {
        // @todo 不合法的路径
    }
    list($class, $method) = explode('.', $requestUri);

    $class = '\\App\\Server\\v1\\' . $class;
    $class = new \ReflectionClass($class);

    $containerBuilder = new ContainerBuilder();
    $containerBuilder->useAutowiring(false);
    $containerBuilder->useAnnotations(false);

    $containerBuilder->addDefinitions([
        Service::class => create(Service::class)->constructor(get('Class'), get('method')),
        'Class' => function() use ($class) {
            return $class->newInstance();
        },
        'method' => $method,
    ]);

    $container = $containerBuilder->build();

//    $middlewareQueue = [];
//
//    $relay = new Relay($middlewareQueue);
//    $relay->handle();

    $service = $container->get(Service::class);

    $server = new \Yar_Server($service);
    $server->handle();
} catch (\Exception $e) {
    (new \Yar_Server(new ErrorController($e->getMessage())))->handle();
}
