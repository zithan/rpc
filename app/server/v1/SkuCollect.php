<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Server;

use App\Model\SkuCollect as SkuCollectModel;

class SkuCollect extends Base
{
    public function collect()
    {
        $model = new SkuCollectModel();
        $ordersIds = $model->countOrders();
        if (!$ordersIds) {
            return yarRtDt('无符合数据');
        }

        $countDts = $model->countSkuByOrders($ordersIds);
        if (!$countDts) {
            return yarRtDt('无符合数据');
        }

        foreach ($countDts as &$countDt) {
            $countDt['id'] = $countDt['goods_id'];
            $countDt['popularity'] = $countDt['market_price'] * $countDt['total'] * 100;
            $countDt['price'] = $countDt['market_price'];
            $countDt['sale_total'] = $countDt['total'];
            $countDt['created'] = time();
        }
        unset($countDt);

        if (!$model->createAll($countDts)) {
            return yarRtDt('统计失败', -1);
        }

        return yarRtDt('统计成功');
    }
}