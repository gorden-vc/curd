<?php
/**
 * Created by PhpStorm.
 * Date: 2021/7/8
 * Time: 10:46 PM
 */

namespace Gorden\Curd\Template;

interface IAutoMake
{
    public function check($flag, $path);

    public function make($flag, $path, $other);
}