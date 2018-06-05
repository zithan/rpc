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
use Predis\Client;

use App\Bootstrap\Service;
use App\Server\Error as ErrorController;

use think\Db;

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

    Db::setConfig(require_once(APP_PATH . '/config/database.php'));

    // @todo 后期修改为路径映射-路由
    $requestUri = strrchr($_SERVER['REQUEST_URI'], '/');
    if (!$requestUri) {
        // @todo 不合法的路径
    }
    $requestUri = ltrim($requestUri, '/');
    if (!strpos($requestUri, '.')) {
        // @todo 不合法的路径
    }
    list($module, $class, $method) = explode('.', $requestUri);

    $class = "\\App\\Server\\$module\\$class";
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
        Client::class=> create(Client::class)->constructor(get('redis_config')),
        'redis_config' => function () {
            return require_once(APP_PATH . '/config/redis.php');
        },
    ]);

    $container = $containerBuilder->build();

//    $middlewareQueue = [];
//
//    $relay = new Relay($middlewareQueue);
//    $relay->handle();

    $service = $container->get(Service::class);
    $redis = $container->get(Client::class);

    $server = new \Yar_Server($service);
    $server->handle();
} catch (\Exception $e) {
    (new \Yar_Server(new ErrorController($e->getMessage())))->handle();
}
