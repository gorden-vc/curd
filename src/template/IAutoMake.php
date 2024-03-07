<?php
/**
 * Created by PhpStorm.
 * Date: 2021/7/8
 * Time: 10:46 PM
 */

namespace Gorden\Curd\Template;

use Symfony\Component\Console\Output\OutputInterface;

interface IAutoMake
{
    public function check($flag, $modelPath, $controllerPath, $validatePath, OutputInterface $output);

    public function make($flag, $modelPath, $controllerPath, $validatePath, $other, OutputInterface $output);
}