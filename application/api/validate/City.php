<?php

namespace app\api\validate;

use think\Validate;

class City extends Validate
{
  protected $rule = [
    'alpha' => 'require|alpha|max:1',
  ];
}