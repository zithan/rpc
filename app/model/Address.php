<?php
namespace App\Model;

use think\Db;

class Address extends Base
{
    public function getList(array $conditions, array $config = [])
    {
        try {
            $page = $config['page'] ?? 1;
            $pageSize = $config['pageSize'] ?? 20;
            // order by [过滤]
            $orderBy = $config['orderBy'] ?? 'ASC';
            $order = ['is_default'=>'desc'];

            return Db::name('address')
                        ->where($conditions)
                        ->order($order)
                        ->page((int)$page, (int)$pageSize)
                        ->field('id,consignee,province,city,area,mobile,tel,desc,zipcode,is_default')
                        ->select();
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getTotal($conditions)
    {
        try {
            return Db::name('address')->where($conditions)->count();
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
