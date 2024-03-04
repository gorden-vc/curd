<?php
/**
 * Created by PhpStorm.
 * Date: 2021/7/8
 * Time: 11:23 PM
 */

namespace Gorden\Curd\Template\Impl;

use Gorden\Curd\Extend\Utils;
use Gorden\Curd\Template\IAutoMake;
use support\Db;
use Symfony\Component\Console\Output\Output;

class ModelAutoMake implements IAutoMake
{
    public function check($table, $path)
    {
        !defined('DS') && define('DS', DIRECTORY_SEPARATOR);

        $modelName = Utils::camelize($table);
        $modelFilePath = base_path() . $path . DS . 'model' . DS . $modelName . '.php';

        if (!is_dir(base_path() . $path . DS . 'model')) {
            mkdir(base_path() . $path . DS . 'model', 0755, true);
        }

        if (file_exists($modelFilePath)) {
//            $output = new Output();
//            $output->error("$modelName.php已经存在");
            exit;
        }
    }

    public function make($table, $path, $other)
    {
        $controllerTpl = dirname(dirname(__DIR__)) . '/tpl/model.tpl';
        $tplContent = file_get_contents($controllerTpl);

        $model = ucfirst(Utils::camelize($table));
        $filePath = empty($path) ? '' : DS . $path;
        $namespace = empty($path) ? '\\' : '\\' . $path . '\\';

        $prefix = config('database.connections.mysql.prefix');
        $column = Db::query('SHOW FULL COLUMNS FROM `' . $prefix . $table . '`');
        $pk = '';
        foreach ($column as $vo) {
            if ($vo['Key'] == 'PRI') {
                $pk = $vo['Field'];
                break;
            }
        }

        $tplContent = str_replace('<namespace>', $namespace, $tplContent);
        $tplContent = str_replace('<model>', $model, $tplContent);
        $tplContent = str_replace('<pk>', $pk, $tplContent);

        file_put_contents(base_path() . $path . DS . 'model' . DS . $model . '.php', $tplContent);
    }
}