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
    public function check($flag, $path, OutputInterface $output);

    public function make($flag, $path, $other);
}