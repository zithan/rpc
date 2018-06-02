<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace Commons\Yar;

class YarClient
{
    public static $serverUrl = 'http://rpc.030.me/';

    // 单个
    public static function Single(string $server, array $params = [])
    {
        try {
            return (new \Yar_Client(self::$serverUrl . $server))->run($params);
        } catch (\Yar_Server_Exception $e) {
            throw new \Yar_Server_Exception($e->getType() . '：' . $e->getMessage());
        }
    }

    // 并发
    public static function Multi(array $callParams, array $loopParams)
    {
        try {
            foreach ($callParams as $callParam) {
                \Yar_Concurrent_Client::call(self::$serverUrl . $callParam[0], 'run', [$callParam[1]], $callParam[2]);
            }

            \Yar_Concurrent_Client::loop($loopParams[0], $loopParams[1]);

            \Yar_Concurrent_Client::reset();
        } catch (\Yar_Server_Exception $e) {
            throw new \Yar_Server_Exception($e->getType() . '：' . $e->getMessage());
        }
    }
}