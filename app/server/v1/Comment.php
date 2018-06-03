<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Server\v1;

use App\Server\Base;

class Comment extends Base
{
    public function sub($zithan, $marin) {
        $data = array('userName'=>'zhangsan','nickName'=>'张三','regTime'=>'2014-12-01 10:10:10');
        return $this->response('Comment.add message...' . $zithan . '...' . $marin, 0, $data);
    }

    public function add($zithan, $marin) {
        return 'comment add ......' . $zithan . '....'. $marin;
    }
}