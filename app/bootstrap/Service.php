<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Bootstrap;

use App\server\Base as BaseController;

class Service
{
    private $class;
    private $method;

    public function __construct(BaseController $class, string $method)
    {
        $this->class = $class;
        $this->method = $method;
    }

    public function run(array $params = [])
    {
        try {
            return call_user_func_array([$this->class, $this->method], $params);
        } catch (\Exception $e) {
            yarRtDt($e->getMessage(), -1);
        }
    }
}
