<?php

namespace app\api\controller;

use think\Controller;
use app\api\model\Item;

class Publish extends Controller
{
  public function index(){
    if(!decodeToken()){
      return json([],401);
    }
    $res = input();
    //验证字段
    $resV = $this->validate($res, 'Item.publish');
    if($resV !== true){
      return json(['err' => $resV]);
    }
    //设置字段
    $res['old_price'] = $res['oldPrice'] ? $res['oldPrice'] : 0;
    unset($res['oldPrice']);
    //提交数据库
    $result = Item::create($res);
    if($result -> getError()){
      return json(['err' => '添加数据库失败']);
    }
    return json($result);
  }
  
  //编辑物品
  public function editItem(){
    if(!decodeToken()){
      return json([],401);
    }
    $req = input();
    $item = Item::get($req['id']);
    //如果取消
    if(isset($req['cancel'])){
      $item -> pic = $req['pic'];
    }else{
      $item -> cate = $req['cate'];
      $item -> desc = $req['desc'];
      $item -> pic = $req['pic'];
      $item -> title = $req['title'];
      $item -> price = $req['price'];
      $item -> old_price = $req['oldPrice'];
    }
    //保存修改
    $res = $item -> save();
    if(!$res){
      return json(['err' => '修改失败']);
    }
    return json($item);
  }
  
  //删除指定物品
  public function deleteItem(){
    if(!input('?id')){
      return json(['err' => '未明确物品ID'], 400);
    }
    //获取物品
    $item_id = input('id');
    $item = Item::get($item_id);
    //删除服务器图片
    $picArr = json_decode($item -> pic, true);
    if(count($picArr) !== 0){
      foreach($picArr as $value){
        unlink(serverPicRoot().$value);
      }
    }
    //删除数据库条目
    if($item -> delete()){
      return json($item);
    }else{
      return json(['err' => '删除出错!']);
    }
  }
}