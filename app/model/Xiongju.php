<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Model;

use think\Db;

class Xiongju extends Base
{
    public function createOne($data)
    {
        try {
            return Db::name('xiongju')->insertGetId($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}