<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Validate;

class IDMustBeNonexistent extends BaseValidate
{
    protected $rule = [
        'id' => 'isNonexistent'
    ];
}
