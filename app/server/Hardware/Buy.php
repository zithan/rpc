<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Server\Hardware;

use App\Model\Hardware\Goods;
use App\Server\Base;

class Buy extends Base
{
    public function search(int $dealerId, array $condition = [], array $config = []) {
        try {
            return Goods::getSKUList($dealerId, $condition, $config);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}