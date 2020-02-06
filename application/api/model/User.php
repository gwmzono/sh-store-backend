<?php

namespace app\api\model;

use think\Model;

class User extends Model
{
  protected $field = ['phone', 'nickname', 'password', 'school'];
  protected $autoWriteTimestamp = 'datetime';
  //status -1禁用 0正常 1管理员 10超管
  protected $insert = [
    'status' => 0,
  ];
  protected function setPasswordAttr($value){
    return password_hash($value, PASSWORD_DEFAULT);
  }
  public function item(){
    return $this->hasMany('Item');
  }
}