<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Model\Hardware;

use think\Db;
use App\Model\Base;
use App\Server\Error as ErrorController;

class Goods extends Base
{
    public static function getSKUList(int $dealerId, array $conditions, array $config = [])
    {
        try {
            $page = $config['page'] ?? 1;
            $pageSize = $config['pageSize'] ?? 20;
            // order by [过滤]
            $orderBy = $config['orderBy'] ?? 'ASC';

            // order fields
            $orderField = $config['orderField'] ?? '';
            // [排序字段]人气、总销量、采购价
            switch ($orderField) {
                case 'popularity':
                    $orderField = 'gg.popularity';
                    break;
                case 'sale_num':
                    $orderField = 'gg.sale_num';
                    break;
                case 'price':
                    $orderField = 'gg.shop_price';
                    break;
                default:
                    $orderField = '';
            }

            $order = [$orderField => $orderBy];
            if (empty($orderField)) {
                $order = ['gg.sale_num','gg.goods_common_id'=>'DESC'];
            }

            // [过滤]掉非法的查询条件，不可以含有三维数组
            array_filter($conditions, function ($item) {
                if (is_array($item)) {
                    return false;
                }
            });
            // [过滤]
            $conditions = filter_var_array($conditions, FILTER_SANITIZE_STRING);

            //return $this->getDealerBlacklist($dealerId);

            // 查询条件
            $map = [];
            $mapOr = [];
            foreach ($conditions as $key => $condition) {
                switch ($key) {
                    case 'brand_id':
                        $map[] = ['g.brand_id', '=', $condition];
                        break;
                    case 'cat_id_1':
                        $map[] = ['g.cat_id_1', '=', $condition];
                        break;
                    case 'cat_id_2':
                        $map[] = ['g.cat_id_2', '=', $condition];
                        break;
                    case 'cat_id_3':
                        $map[] = ['g.cat_id_3', '=', $condition];
                        break;
                    case 'kw':
                        $kws = explode(' ', $conditions['kw']);
                        array_walk($kws, function(&$value, $key, $fix = '%') {
                            $value = $fix . $value . $fix;
                        });
                        $map[] = ['g.goodsname', 'like', $kws];
                        $mapOr[] = ['g.goods_sn', 'like', '%' . trim($condition) . '%', 'or'];
                        break;
                }
            }

            // '上下架，1上架，；0，下架'
            $map[] = ['g.is_on_sale', '=', 1];
            // '采购平台显示 1 显示 0 隐藏'
            $map[] = ['g.buy_show', '=', 1];
            // '审核状态1未审核2审核未通过3审核通过'
            $map[] = ['g.review_status', '=', 3];

            // 获取经销商的商品黑名单
            $dealerBlacklist = self::getDealerBlacklist($dealerId);
            if ($dealerBlacklist) {
                $map[] = ['g.goods_common_id', 'not in', $dealerBlacklist];
            }

            // 查询
            $subQuery = Db::name('goods')->alias('g')
                ->field('g.goods_id,g.goods_common_id,g.sale_num,g.shop_price,sc.popularity')
                ->join('dealer_black db', 'db.goods_common_id=g.goods_common_id and db.dealer_id=' . $dealerId, 'LEFT')
                ->join('sku_collect sc', 'sc.id=g.goods_id', 'LEFT')
                ->where($map)
                ->whereOr($mapOr)
                ->whereNull('db.goods_common_id')
                ->order('g.default desc')
                ->buildSql();
//            return $subQuery;

            $list = Db::table($subQuery . ' gg')
                ->group('gg.goods_common_id')
                ->order($order)
                ->page((int)$page, (int)$pageSize)
                //->fetchSql(true)
                ->column('gg.goods_id');

            // count
            $total = 0;
            if ($list) {
                $total = Db::name('goods')->alias('g')
                    ->join('dealer_black db', 'db.goods_common_id=g.goods_common_id and db.dealer_id=' . $dealerId, 'LEFT')
                    ->join('sku_collect sc', 'sc.id=g.goods_id', 'LEFT')
                    ->where($map)
                    ->whereOr($mapOr)
                    ->whereNull('db.goods_common_id')
                    ->group('g.goods_common_id')
                    ->count();
            }

            return ['list' => $list, 'total' => $total];
        } catch (\Exception $e) {
            (new \Yar_Server(new ErrorController($e->getMessage())))->handle();
        }
    }

    // 获取经销商的商品黑名单
    private static function getDealerBlacklist(int $dealerId)
    {
        return Db::name('dealer_group_blackgoods')->alias('dgb')
            ->distinct(true)
            ->join('dealer_to_group dtg', 'dtg.sg_id = dgb.group_id', 'INNER')
            ->where('dtg.dealer_id', '=', $dealerId)
            //->fetchSql(true)
            ->column('dgb.goods_common_id');
    }
}