<?php

namespace Gorden\Curd\Common;

use support\Db;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder;

class Util
{
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
}