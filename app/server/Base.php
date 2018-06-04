<?php

namespace App\Server;

class Base {

    private static $signs = array(
        'sign1',
        'sign2'
        // ....
    );

    public function __construct()
    {
        // 认证
        // 限流
    }

    protected function checkSignature()
    {
        if (empty($sign)) {
            return false;
        }
        ksort($params);
        $signStr = '';
        foreach ($params as $key => $val) {
            if (empty($val) || $val == $sign) continue;
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        foreach (self::$signs as $v) {
            if (md5($signStr . $v) === $sign) {
                return true;
            }
        }
        return false;
    }

    protected function response(string $message, int $errCode = 0, array $data = [])
    {
        return [
            'message' => $message,
            'errCode' => $errCode,
            'data' => $data
        ];
    }

    protected function pushMQ()
    {
        
    }
}
