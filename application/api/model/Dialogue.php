<?php

namespace app\api\model;

use think\Model;

class Dialogue extends Model
{
  protected $field = ['from', 'to', 'message', 'item_id'];
  protected $autoWriteTimestamp = 'datetime';
  
  protected function getCreateTimeAttr($value){
    return strtotime($value);
  }
  protected function getUpdateTimeAttr($value){
    return strtotime($value);
  }
  
  public function item(){
    return $this->belongsTo('item');
  }
  //控制器判断调用哪一个
  public function fromUser(){
    return $this->belongsTo('User', 'from');
  }
  public function toUser(){
    return $this->belongsTo('User', 'to');
  }
}