<?php

namespace app\api\validate;

use think\Validate;

class User extends Validate
{
  protected $rule = [
    'phone' => 'require|checkPhone',
    'password' => 'require|checkPW',
    'nickname' => 'length:2,16',
    'school' => 'require|chs|length:2,30',
  ];
  protected $scene = [
    'register' => ['phone', 'password', 'nickname','school'],
    'login' => ['phone', 'password'],
    'vPhone' => ['phone'],
    'vNickname' => ['nickname'],
  ];
  protected function checkPhone($value){
    return 1 === preg_match('/^1[3-9]\d{9}$/', $value);
  }
  protected function checkPW($value){
    return 1 === preg_match('/^[a-zA-Z]\w{7,31}$/', $value);
  }
}