<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Server\Common;

use App\Server\Base;
use App\Validate\Address as addressVldt;

class Address extends Base
{
    /**
     * 地址列表
     *
     * @return [type] [description]
     * @author hutong
     * @date   2018-06-04T21:46:34+080
     */
    public function list(array $condition = [], array $config = [])
    {
        $validate = new addressVldt();
        $validate->goCheck($params);

        //过滤(限定字段，非法字符)
        $data = $validate->getDataByRule($params);
        // 简单处理
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);

        $pageIdx = $this->getPageIdx($data['page'], $data['pageSize']);

        $conditions = array(
            'type' => $data['type'],
            'kid' => $data['kid'],
        );

        $config = array(
            'page' =>
        );

        $result = (new \App\Model\Address())->getList($conditions, $pageIdx[0], $pageIdx[1]);

        return yarRtDt('执行成功', 0, [$result]);
    }

    /**
     * 查看地址
     *
     * @return [type] [description]
     * @author hutong
     * @date   2018-06-04T21:46:51+080
     */
    public function find()
    {

    }

    /**
     * 保存地址
     *
     * @return [type] [description]
     * @author hutong
     * @date   2018-06-04T21:47:23+080
     */
    public function save()
    {

    }

    /**
     * 地址删除
     *
     * @return [type] [description]
     * @author hutong
     * @date   2018-06-04T21:47:50+080
     */
    public function delete()
    {

    }

    /**
     * 设置为默认地址
     *
     * @author hutong
     * @date   2018-06-04T21:48:12+080
     */
    public function setMain()
    {

    }
}
