<?php

namespace app<namespace>validate;

use think\Validate;

class <model>Validate extends Validate
{
    protected $rule = <rule>;
    protected $message = [];

    protected $scene = [
        'insert' => <scene>,
        'update' => <scene>
    ];
}