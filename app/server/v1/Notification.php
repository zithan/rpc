<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Server\v1;

use App\Server\Base;

// set_time_limit(80);
class Notification extends Base
{
    // 订单通知
    const ORDERS_REPLY = 1;

    // 服务端push消息队列，创造消息-消息入队
    // 添加通知：对相应的通知对象的notification_count+1
    // 根据指定的渠道把通知转换过为对应的消息
    public function push(int $type, array $data)
    {

    }

    // 客户端pull消息队列-长轮询：
    // 场景1，发起通知连接时，队列里正好有消息；
    // 场景二，发起通知连接时，队列里无消息;
    // 场景三，新消息来时，正好有通知连接在
    // 场景四，新消息来时，没有通知连接
    // id,type,,read_at,created_at,updated_at,data(json)
    //data(json{"id":1,"content":"厂商通知经销商",
    //"seller_id","seller_name","seller_avatar","link","orders_id","orders_title"})
    public function list()
    {

    }

    // 标记消息通知为已读
    // /user/read/notifications/{notification_id}
    public function read()
    {
        // markAsRead:notification_count=0
    }

    // 通知统计,拉redis
    public function count()
    {

    }
}