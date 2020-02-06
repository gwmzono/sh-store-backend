<?php

namespace app\api\model;

use think\Model;

class Item extends Model
{
  protected $field = true;
  protected $autoWriteTimestamp = 'datetime';
  
  protected function getCreateTimeAttr($value){
    return strtotime($value);
  }
  protected function getUpdateTimeAttr($value){
    return strtotime($value);
  }
  
  public function user(){
    return $this -> belongsTo('User');
  }
}