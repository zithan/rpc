<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Server\v1;

use App\Model\Goods;
use App\Server\Base;

class Buy extends Base
{
    public function search(array $condition = [], array $config = []) {

        (new Goods())->getSKUList($condition, $config);
    }
}