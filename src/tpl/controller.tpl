<?php

namespace app<namespace>controller;

use app<modelNamespace>model\<model>;
use app<namespace>validate\<model>Validate;
use Gorden\Curd\Controller\CurdController;

class <controller> extends CurdController
{
    public function __construct()
    {
        $this->model = new <model>();
        $this->validateObject = new <model>Validate();
    }
}
