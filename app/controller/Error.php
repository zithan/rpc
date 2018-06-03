<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Controller;


class Error extends Base
{
    private $msg;

    public function __construct($msg)
    {
        $this->msg = $msg;
    }

    public function run()
    {
        throw new \Exception($this->msg);
    }
}