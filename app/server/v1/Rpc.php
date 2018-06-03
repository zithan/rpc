<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Server\v1;

use App\Bootstrap\Service;

class Rpc
{
    private $foo;

    public function __construct(string $foo)
    {
        $this->foo = $foo;
    }

    public function __invoke($server)
    {
        $server = new \Yar_Server(new Service(new Comment(), 'add'));
        $server->handle();
    }
}