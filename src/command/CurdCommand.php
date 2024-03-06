<?php
/**
 * Created by PhpStorm.
 * Date: 2021/7/8
 * Time: 8:23 PM
 */

namespace app\command;

use Gorden\Curd\Strategy\AutoMakeStrategy;
use Gorden\Curd\Template\Impl\ControllerAutoMake;
use Gorden\Curd\Template\Impl\ModelAutoMake;
use Gorden\Curd\Template\Impl\ValidateAutoMake;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CurdCommand extends Command
{

    protected static $defaultName = 'curd';
    protected static $defaultDescription = 'auto make curd file';

    protected function configure()
    {
        $this->setName('auto curd')
            ->addOption('table', 't', InputOption::VALUE_OPTIONAL, 'the table name', null)
            ->addOption('name', 'c', InputOption::VALUE_OPTIONAL, 'the controller name', null)
            ->addOption('controller_path', 'x', InputOption::VALUE_OPTIONAL, 'the controller path', null)
            ->addOption('model_path', 'y', InputOption::VALUE_OPTIONAL, 'the model path', null)
            ->addOption('validate_path', 'z', InputOption::VALUE_OPTIONAL, 'the validate path', null)
            ->setDescription('auto make curd file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = $input->getOption('table');
        if (!$table) {
            $output->write("请输入 -t 表名");
            exit;
        }

        $controller = $input->getOption('name');
        if (!$controller) {
            $output->write("请输入 -c 控制器名");
            exit;
        }

        $controllerPath = $input->getOption('controller_path');
        if (!$controllerPath) {
            $controllerPath = '';
        }$modelPath = $input->getOption('model_path');
        if (!$modelPath) {
            $modelPath = '';
        }$validatePath = $input->getOption('validate_path');
        if (!$validatePath) {
            $validatePath = '';
        }

        $context = new AutoMakeStrategy();

        // 执行生成controller策略
        $context->Context(new ControllerAutoMake());
        $context->executeStrategy($controller, $controllerPath, $table, $output);

        // 执行生成model策略
        $context->Context(new ModelAutoMake());
        $context->executeStrategy($table, $modelPath, '', $output);

        // 执行生成validate策略
        $context->Context(new ValidateAutoMake());
        $context->executeStrategy($table, $validatePath, '', $output);

        $output->write("auto make curd success");

        return self::SUCCESS;
    }
}