<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Validate;

class Xiongju extends BaseValidate
{
    protected $rule = [
        'type' => 'require|between:0,4',
        'kid' => 'require|isPositiveInteger',
        'account' => 'require|alphaDash|max:64',
        'nicename' => 'require|chsDash',
        'password' => 'require|isNotEmpty|min:6',
        'identity' => 'require|between:1,3',
        'contact_name' => 'require|chsDash',
        'contact_tel' => 'require|isMobile',
        'portrait' => 'require|isNotEmpty'
    ];

    protected $scene = [
        'create' => ['type', 'kid', 'account', 'nicename', 'password', 'identity',
            'contact_name', 'contact_tel', 'portrait'],
    ];

    protected $message = [
//        'nicename' => 'xxxxx'
    ];
}