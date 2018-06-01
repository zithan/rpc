<?php
/**
 * Created by zithan.
 * User: zithan <zithan@163.com>
 */

/*
/*
 * 公用方法文件 请按格式新增方法
 * @author zithan@163.com
 *
 * if(!function_exists('exp')) {
 *     function exp()
 *     {
 *
 *     }
 * }
 *
 */

if (!function_exists('yarRtDt')) {
    function yarRtDt(string $message = '', array $data = [], int $errCode = 0)
    {
        return [
            'message' => $message,
            'data' => $data,
            'errCode' => $errCode
        ];
    }
}

/**
 * 优化var_dump()调试使用
 * @param mixed $var 变量
 * @param boolean browser是否浏览器输出
 * @return void|string
 */
if (!function_exists('dump')) {
    function dump($var, $browser = false)
    {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
        if ($browser) {
            $output = '<pre>' . PHP_EOL . $output . '</pre>';
        }

        echo $output;
        exit;
    }
}
