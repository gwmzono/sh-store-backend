<?php
namespace app\index\controller;

use app\api\model\Item;

class Index
{
  public function index()
  {
    $itemM = new Item;
    $res = $itemM->where('school', '扬州大学')
          ->find();
    $res -> user;
    dump($res->getData());
  }
  
}
