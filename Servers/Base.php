<?php

namespace Rpc\Servers;

class Base {
    public function __construct() {
        //判断扩展是否存在
        if(!extension_loaded('yar'))
            die('yar not support');

        $server = new \Yar_Server($this);
        $server->handle();
    }
}
