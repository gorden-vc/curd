<?php

namespace app<namespace>controller;

use app<namespace>model\<model>;
use app<namespace>validate\<model>Validate;

class <controller> extends Base
{
    public function __construct()
    {
        $this->model = new <model>();
        $this->validateObject = new <model>Validate();
    }
}
