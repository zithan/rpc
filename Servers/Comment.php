<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace Rpc\Servers;

class Comment extends Base
{
    /**
     * Sub
     */
    public function sub($a, $b) {
        return $a - $b;
    }
}