<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Model;

use think\Db;

class SkuCollect extends Base
{
    // 保存多个sku的销量和人气
    public function createAll(array $data)
    {
        try {
            $sql = 'TRUNCATE table `xjw_sku_collect`';
            Db::execute($sql);

            return Db::name('sku_collect')->strict(false)->insertAll($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    // 获取支付状态为待付尾款和支付完成的一周订单
    public function countOrders()
    {
        try {
            return Db::name('order_info')
                ->whereTime('pay_time', 'week')
                ->whereIn('pay_status', [2, 9])
                ->column('order_id');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    // 根据订单ids统计每个sku的销量
    public function countSkuByOrders(array $ordersIds)
    {
        try {
            return Db::name('order_goods')->alias('og')
                ->field('og.goods_id, g.market_price, count(og.rec_id) as total')
                ->join('goods g', 'g.goods_id=og.goods_id', 'LEFT')
                ->whereIn('og.order_id', $ordersIds)
                ->group('og.goods_id')
                ->select();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}