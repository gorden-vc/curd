<?php

use support\Response;
/**
 * 统一分页返回
 * @param $list
 * @return array
 */
if (!function_exists('pageReturn')) {

    function pageReturn($list)
    {
        if (0 == $list['code']) {
            return ['code' => 0, 'msg' => 'ok', 'count' => $list['data']->total(), 'data' => $list['data']->all()];
        }

        return ['code' => 0, 'msg' => 'ok', 'count' => 0, 'data' => []];
    }
}

/**
 * 下划线转驼峰
 */
if (!function_exists('toCamelCase')) {
    function toCamelCase($str)
    {
        $array = explode('_', $str);
        $result = $array[0];
        $len = count($array);
        if ($len > 1) {
            for ($i = 1; $i < $len; $i++) {
                $result .= ucfirst($array[$i]);
            }
        }
        return $result;
    }
}

/**
 * 正常数据返回
 */
if (!function_exists('json_success')) {

    function json_success($message, $data = '', $options = JSON_UNESCAPED_UNICODE)
    {
        $return = [
            'code' => 200,
            'message' => $message,
            'data' => $data,
        ];

        return new Response(200, ['Content-Type' => 'application/json'], json_encode($return, $options));
    }
}

/**
 * 错误数据返回
 */
if (!function_exists('json_fail')) {

    function json_fail($message, $options = JSON_UNESCAPED_UNICODE)
    {
        $return = [
            'code' => 0,
            'message' => $message,
            'data' => ''
        ];

        return new Response(200, ['Content-Type' => 'application/json'], json_encode($return, $options));
    }
}
