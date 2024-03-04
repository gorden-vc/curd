<?php

namespace Gorden\Curd\Common;

use support\Db;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder;
use support\Response;

class Util
{
    /**
     * 密码哈希
     * @param $password
     * @param string $algo
     * @return false|string|null
     */
    public static function passwordHash($password, string $algo = PASSWORD_DEFAULT)
    {
        return password_hash($password, $algo);
    }

    /**
     * 验证密码哈希
     * @param $password
     * @param $hash
     * @return bool
     */
    public static function passwordVerify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * 获取webman-admin数据库连接
     * @return Connection
     */
    public static function db(): Connection
    {
        return Db::connection('mysql');
    }

    /**
     * 获取SchemaBuilder
     * @return Builder
     */
    public static function schema(): Builder
    {
        return Db::schema('mysql');
    }

    /**
     * 数据库字符串转义
     * @param $var
     * @return false|string
     */
    public static function pdoQuote($var)
    {
        return Util::db()->getPdo()->quote($var, \PDO::PARAM_STR);
    }

    /**
     * 变量或数组中的元素只能是字母数字下划线组合
     * @param $var
     * @return mixed
     * @throws \Exception
     */
    public static function filterAlphaNum($var)
    {
        $vars = (array)$var;
        array_walk_recursive($vars, function ($item) {
            if (is_string($item) && !preg_match('/^[a-zA-Z_0-9]+$/', $item)) {
                throw new \Exception('参数不合法');
            }
        });
        return $var;
    }

    public static function fieldDefaultLength()
    {
        return [
            'string' => 255,    // 对应varchar
            'integer' => 11,
            'decimal' => '10,2',
            'dateTime' => 0
        ];
    }

    /**
     * 表单类型到插件的映射
     * @return \string[][]
     */
    public static function methodControlMap(): array
    {
        return [
            //method=>[控件]
            'integer' => ['InputNumber'],
            'string' => ['Input'],
            'text' => ['TextArea'],
            'date' => ['DatePicker'],
            'enum' => ['Select'],
            'float' => ['Input'],

            'tinyInteger' => ['InputNumber'],
            'smallInteger' => ['InputNumber'],
            'mediumInteger' => ['InputNumber'],
            'bigInteger' => ['InputNumber'],

            'unsignedInteger' => ['InputNumber'],
            'unsignedTinyInteger' => ['InputNumber'],
            'unsignedSmallInteger' => ['InputNumber'],
            'unsignedMediumInteger' => ['InputNumber'],
            'unsignedBigInteger' => ['InputNumber'],

            'decimal' => ['Input'],
            'double' => ['Input'],

            'mediumText' => ['TextArea'],
            'longText' => ['TextArea'],

            'dateTime' => ['DateTimePicker'],

            'time' => ['DateTimePicker'],
            'timestamp' => ['DateTimePicker'],

            'char' => ['Input'],

            'binary' => ['Input'],

            'json' => ['input']
        ];
    }

    /**
     * @Desc 正确返回
     * @Author Gorden
     * @Date 2024/2/28 14:48
     *
     * @param $message
     * @param $data
     * @return Response
     */
    public static function jsonSuccess($message = '', $data = [], $options = JSON_UNESCAPED_UNICODE)
    {
        $return = [
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ];

        return new Response(200, ['Content-Type' => 'application/json'], json_encode($return, $options));
    }

    /**
     * @Desc 错误返回
     * @Author Gorden
     * @Date 2024/2/28 15:41
     *
     * @param $message
     * @param $data
     * @param $options
     * @return Response
     */
    public static function jsonFail($message = 'fail', $data = [], $options = JSON_UNESCAPED_UNICODE)
    {
        $return = [
            'code' => 0,
            'message' => $message,
            'data' => $data
        ];

        return new Response(200, ['Content-Type' => 'application/json'], json_encode($return, $options));
    }

    /**
     * @Desc 异常返回
     * @Author Gorden
     * @Date 2024/2/28 15:42
     *
     * @param $message
     * @param $data
     * @param $options
     * @return Response
     */
    public static function jsonException($message = 'fail', $data = [], $options = JSON_UNESCAPED_UNICODE)
    {
        $return = [
            'code' => 500,
            'message' => $message,
            'data' => $data
        ];

        return new Response(500, ['Content-Type' => 'application/json'], json_encode($return, $options));
    }
}