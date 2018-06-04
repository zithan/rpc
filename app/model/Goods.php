<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Model;

class Goods extends Base
{
    public function getSKUList(array $condition, array $config = [])
    {
        // 组合limit语句,
        $page = $config['page'] ? (int)$config['page'] : 1;
        $pageSize = $config['pageSize'] ? (int)$config['pageSize'] : 20;
        $pageStr = sprintf(' LIMIT %d,%d', ($page - 1) * $pageSize, $pageSize);

        // order fields
        $orderField = $config['orderField'] ?? 'default';
        // [过滤]
        switch ($orderField) {
            case 'title':
                $orderField = 'nd.ad_title';
                break;
            default:
                $orderField = 'nd.id';
        }
        // order by [过滤]
        $orderBy = (isset($config['orderBy']) && (1 == $config['orderBy'])) ? 'DESC' : 'ASC';
        // 组合order语句
        $orderStr = sprintf(' ORDER BY %s %s', $orderField, $orderBy);

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
                case 'title':
                    $map[] = 'nd.ad_title LIKE "%' . $condition . '%"';
                    break;
                case 'type':
                    $map[] = 'nd.type = ' . (int)$condition;
                    break;
                case 'kid':
                    $map[] = 'nd.kid = ' . (int)$condition;
                    break;
            }
        }

        // 组合where语句
        $whereStr = empty($map) ? '' : ' WHERE ' . implode(' AND ', $map);

        $selectTableStr = 'SELECT %s FROM ' . $this->table . ' nd ';

        // 组合join语句
        $joinStr = '';

        // 查询字段
        $fields = 'id,ad_title,detail_type,source_name,status,audit_status,'
            . 'show_count,click_count,created,showtime_start,showtime_end';

        $sql = sprintf($selectTableStr, $fields) . $joinStr . $whereStr . $orderStr . $pageStr;
        //var_dump($sql);exit;
        $list = $this->db->select($sql);

        if (! $list) {
            $list = [];
            $total = 0;
        } else {
            $fields = 'COUNT(*) AS `count`';
            $sqlTotal = sprintf($selectTableStr, $fields) . $joinStr . $whereStr;
            $total = $this->db->getOne($sqlTotal);
        }

        return [
            'list' => $list,
            'total' => $total
        ];
    }
}