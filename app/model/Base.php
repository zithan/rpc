<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Model;

use think\Db;

class Base
{
    public function __construct()
    {
        Db::setConfig(require_once(APP_PATH . '/config/database.php'));
    }
}