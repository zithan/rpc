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
    public function test()
    {
        return 'test';
    }

    public function getSKUList(array $conditions, array $config = [])
    {
        try {
            $page = $config['page'] ?? 1;
            $pageSize = $config['pageSize'] ?? 20;
            // order by [过滤]
            $orderBy = $config['orderBy'] ?? 'ASC';

            // order fields
            $orderField = $config['orderField'] ?? 'default';
            // [过滤]
            switch ($orderField) {
                case 'title':
                    $orderField = 'nd.ad_title';
                    break;
                default:
                    $orderField = 'gg.sale_num,gg.goods_common_id';
            }

            $order = [$orderField => $orderBy];

            // [过滤]掉非法的查询条件，不可以含有三维数组
            array_filter($conditions, function ($item) {
                if (is_array($item)) {
                    return false;
                }
            });
            // [过滤]
            $conditions = filter_var_array($conditions, FILTER_SANITIZE_STRING);

            // 查询条件
            $map = [];
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
                        $map[] = ['g.goods_sn', 'like', '%' . trim($condition) . '%', 'OR'];
                        break;
                }
            }

            // '上下架，1上架，；0，下架'
            $map[] = ['g.is_on_sale', '=', 1];
            // '采购平台显示 1 显示 0 隐藏'
            $map[] = ['g.buy_show', '=', 1];
            // '审核状态1未审核2审核未通过3审核通过'
            $map[] = ['g.review_status', '=', 3];

            // @todo 获取经销商的商品黑名单

            // 查询
            $subQuery = Db::name('goods')->alias('g')
                ->field('g.goods_id,g.goods_common_id')
                ->join('dealer_black db', 'db.goods_common_id=g.goods_common_id and db.dealer_id=1', 'LEFT')
                ->order('g.default desc')
                ->buildSql();
//            return $subQuery;

            Db::table($subQuery . ' gg')
                ->field('gg.goods_id')
                ->group('gg.goods_common_id')
                ->order('gg.sale_num,gg.goods_common_id desc')
                ->page((int)$page, (int)$pageSize)
                ->select();
        } catch (\Exception $e) {
            (new \Yar_Server(new ErrorController($e->getMessage())))->handle();
        }
    }
}