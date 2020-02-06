<?php

namespace app\api\validate;

use think\Validate;

class School extends Validate
{
  protected $rule = [
    'city' => 'require|chs',
    'name' => 'require|checkSchoolName',
  ];
  protected $scene = [
    'select' => ['city'],
    'search' => ['name'],
  ];
  protected function checkSchoolName($value){
    return 1 === preg_match('/^[\x{4e00}-\x{9fa5} ]{2,20}$/u', $value);
  }
}