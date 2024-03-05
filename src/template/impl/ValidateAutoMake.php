<?php

namespace Gorden\Curd\Template\Impl;

use Gorden\Curd\Extend\Utils;
use Gorden\Curd\Template\IAutoMake;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarExporter\VarExporter;
use Symfony\Component\Console\Output\Output;
use support\Db;

class ValidateAutoMake implements IAutoMake
{
    public function check($table, $path, OutputInterface $output)
    {
        $validateName = Utils::camelize($table) . 'Validate';
        $validateFilePath = base_path() . $path . DS . 'validate' . DS . $validateName . '.php';

        if (!is_dir(base_path() . $path . DS . 'validate')) {
            mkdir(base_path() . $path . DS . 'validate', 0755, true);
        }

        if (file_exists($validateFilePath)) {
            $output->write("$validateName.php22已经存在");
            exit;
        }
    }

    public function make($table, $path, $other)
    {
        $validateTpl = dirname(dirname(__DIR__)) . '/tpl/validate.tpl';
        $tplContent = file_get_contents($validateTpl);

        $model = ucfirst(Utils::camelize($table));
        $filePath = empty($path) ? '' : DS . $path;
        $namespace = empty($path) ? '\\' : '\\' . $path . '\\';

        $prefix = config('database.connections.mysql.prefix');
        $column = Db::query('SHOW FULL COLUMNS FROM `' . $prefix . $table . '`');
        $rule = [];
        $attributes = [];
        foreach ($column as $vo) {
            $rule[$vo['Field']] = 'require';
            $attributes[$vo['Field']] = $vo['Comment'];
        }

        $ruleArr = VarExporter::export($rule);
        $attributesArr = VarExporter::export($attributes);

        $tplContent = str_replace('<namespace>', $namespace, $tplContent);
        $tplContent = str_replace('<model>', $model, $tplContent);
        $tplContent = str_replace('<rule>', '' . $ruleArr, $tplContent);
        $tplContent = str_replace('<attributes>', $attributesArr, $tplContent);

        file_put_contents(base_path() . $filePath . DS . 'validate' . DS . $model . 'Validate.php', $tplContent);
    }
}