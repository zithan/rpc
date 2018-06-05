<?php
namespace App\Validate;

class Address extends BaseValidate
{
    protected $rule = [
        'type' => 'require|between:1,3',
        'kid' => 'require|isPositiveInteger',
        'page' => 'require|isPositiveInteger',
        'pageSize' => 'require|isPositiveInteger',
    ];

    protected $scene = [
        'list' => ['type', 'kid', 'page', 'pageSize'],
    ];

    protected $message = [
//        'nicename' => 'xxxxx'
    ];
}
