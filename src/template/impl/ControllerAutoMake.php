<?php
/**
 * Created by PhpStorm.
 * Date: 2021/7/8
 * Time: 10:49 PM
 */

namespace Gorden\Curd\Template\Impl;

use Gorden\Curd\Extend\Utils;
use Gorden\Curd\Template\IAutoMake;
use support\Db;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerAutoMake implements IAutoMake
{
    public function check($controller, $modelPath, $controllerPath, $validatePath, OutputInterface $output)
    {
        !defined('DS') && define('DS', DIRECTORY_SEPARATOR);

        $controller = ucfirst($controller);
        $controllerFilePath = app_path('/') . $controllerPath . DS . 'controller' . DS . $controller . '.php';

        if (!is_dir(app_path('/') . $controllerPath . DS . 'controller')) {
            mkdir(app_path('/') . $controllerPath . DS . 'controller', 0755, true);
        }

        if (file_exists($controllerFilePath)) {
            $output->writeln("$controller.php已经存在");
            exit;
        }
    }

    public function make($controller, $modelPath, $controllerPath, $validatePath, $table, OutputInterface $output)
    {
        $controllerTpl = dirname(dirname(__DIR__)) . '/tpl/controller.tpl';
        $tplContent = file_get_contents($controllerTpl);

        $controller = ucfirst($controller);
        $model = ucfirst(Utils::camelize($table));
        $filePath = empty($controllerPath) ? '' : DS . $controllerPath;
        $namespace = empty($controllerPath) ? '\\' : '\\' . $controllerPath . '\\';
        $modelNamespace = empty($modelPath) ? '\\' : '\\' . $modelPath . '\\';

        $prefix = config('database.connections.mysql.prefix');
        $column = Db::select('SHOW FULL COLUMNS FROM `' . $prefix . $table . '`');

        $pk = '';
        foreach ($column as $vo) {
            if ($vo->Key == 'PRI') {
                $pk = $vo->Field;
                break;
            }
        }

        $tplContent = str_replace('<namespace>', $namespace, $tplContent);
        $tplContent = str_replace('<modelNamespace>', $modelNamespace, $tplContent);
        $tplContent = str_replace('<controller>', $controller, $tplContent);
        $tplContent = str_replace('<model>', $model, $tplContent);
        $tplContent = str_replace('<pk>', $pk, $tplContent);

        if (file_put_contents(app_path('/') . $filePath . DS . 'controller' . DS . $controller . '.php', $tplContent)) {
            $output->writeln("Generate app".$namespace."controller\\".$controller.".php Success");
        } else {
            $output->writeln("Generate app".$namespace."controller\\".$controller.".php Fail");
        }

        // 检测base是否存在
//        if (!file_exists(base_path() . $filePath . DS . 'controller' . DS . 'Base.php')) {
//
//            $controllerTpl = dirname(dirname(__DIR__)) . '/tpl/baseController.tpl';
//            $tplContent = file_get_contents($controllerTpl);
//
//            $tplContent = str_replace('<namespace>', $namespace, $tplContent);
//
//            file_put_contents(base_path() . $filePath . DS . 'controller' . DS . 'Base.php', $tplContent);
//        }
    }
}