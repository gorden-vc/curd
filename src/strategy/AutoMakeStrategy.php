<?php
/**
 * Created by PhpStorm.
 * Date: 2021/7/8
 * Time: 10:54 PM
 */

namespace Gorden\Curd\Strategy;

use Gorden\Curd\Template\IAutoMake;
use Symfony\Component\Console\Output\OutputInterface;

class AutoMakeStrategy
{
    protected $strategy;

    public function Context(IAutoMake $obj)
    {
        $this->strategy = $obj;
    }

    public function executeStrategy($flag, $modelPath, $controllerPath, $validatePath, $other, OutputInterface $output)
    {
        $this->strategy->check($flag, $modelPath, $controllerPath, $validatePath, $output);
        $this->strategy->make($flag, $modelPath, $controllerPath, $validatePath, $other);
    }
}