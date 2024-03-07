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
    public function check($table, $modelPath, $controllerPath, $validatePath, OutputInterface $output)
    {
        $validateName = Utils::camelize($table) . 'Validate';
        $validateFilePath = app_path('/') . $validatePath . DS . 'validate' . DS . $validateName . '.php';

        if (!is_dir(app_path('/') . $validatePath . DS . 'validate')) {
            mkdir(app_path('/') . $validatePath . DS . 'validate', 0755, true);
        }

        if (file_exists($validateFilePath)) {
            $output->write("$validateName.php已经存在");
            exit;
        }
    }

    public function make($table, $modelPath, $controllerPath, $validatePath, $other, OutputInterface $output)
    {
        $validateTpl = dirname(dirname(__DIR__)) . '/tpl/validate.tpl';
        $tplContent = file_get_contents($validateTpl);

        $model = ucfirst(Utils::camelize($table));
        $filePath = empty($validatePath) ? '' : DS . $validatePath;
        $namespace = empty($validatePath) ? '\\' : '\\' . $validatePath . '\\';

        $prefix = config('database.connections.mysql.prefix');
        $column = Db::select('SHOW FULL COLUMNS FROM `' . $prefix . $table . '`');
        $rule = [];
        $scene = [];
        foreach ($column as $vo) {
            $rule[$vo->Field] = $vo->Null == 'NO' ? 'require' : '';
            if ($vo->Key != 'PRI'){
                $scene[] = $vo->Field;
            }
        }

        $ruleArr = VarExporter::export($rule);
        $sceneArr = VarExporter::export($scene);

        $tplContent = str_replace('<namespace>', $namespace, $tplContent);
        $tplContent = str_replace('<model>', $model, $tplContent);
        $tplContent = str_replace('<rule>', '' . $ruleArr, $tplContent);
        $tplContent = str_replace('<scene>', $sceneArr, $tplContent);

        if (file_put_contents(app_path('/') . $filePath . DS . 'validate' . DS . $model . 'Validate.php', $tplContent)) {
            $output->writeln("Generate app".$namespace."validate\\".$model."Validate.php Success");
        } else {
            $output->writeln("Generate app".$namespace."validate\\".$model."Validate.php Fail");
        }
    }
}