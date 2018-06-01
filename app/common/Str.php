<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

namespace App\Common;

class Str
{
    public static function genRandChar(int $length = 6)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];
        }

        return $str;
    }


    public static function genPassword(string $password, string $salt = '')
    {
        return md5(md5($password) . $salt);
    }
}