<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Server;

class Comment extends Base
{
    public function sub($zithan, $marin) {
        return 'comment sub ......' . $zithan . '....'. $marin;
    }

    public function add($zithan, $marin) {
        return 'comment add ......' . $zithan . '....'. $marin;
    }
}