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
    public function search(int $dealerId, array $conditions = [], array $config = []) {
        try {
            // [过滤]掉非法的查询条件，不可以含有三维数组
            array_filter($conditions, function ($item) {
                if (is_array($item)) {
                    return false;
                }
            });
            // [过滤]
            $conditions = filter_var_array($conditions, FILTER_SANITIZE_STRING);

            $result = Goods::getList($dealerId, $conditions, $config);
            return $this->response('查询结果', 0, $result);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}