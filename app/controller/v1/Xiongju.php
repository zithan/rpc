<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Controller\v1;

use App\Validate\Xiongju as XiongjuVldt;

class Xiongju extends Base
{
    public function create($params)
    {
        // 验证，
        $validate = new XiongjuVldt();
        $validate->goCheck($params);

        //过滤(限定字段，非法字符)
        $data = $validate->getDataByRule($params);
        // 简单处理
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);

        // 组合数据
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created'] = time();

        // 保存
        $rs = (new \App\Model\Xiongju())->createOne($data);

        return yarRtDt('执行成功', 0, [$rs]);
    }
}