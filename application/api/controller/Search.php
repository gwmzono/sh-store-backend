<?php

namespace app\api\controller;

use think\Controller;
use app\api\model\Item;

class Search extends Controller
{
  //用户搜索
  public function index(){
    $req = input();
    //验证字段
    $reqV = $this->validate($req, 'Item.search');
    if($reqV !== true){
      return json(['err' => $reqV]);
    }
    //检索数据库
    $item = Item::where('school',$req['school'])
                ->where('title', 'like', getQueryString($req['keyword']))
                ->page($req['page'], $req['size']);  //第几页和每页数量
    if(isset($req['sort'])){
      switch($req['sort']){
        case 'time': $item -> order('create_time', 'desc'); break;
        case 'price': $item -> order('price'); break;
        default: $item -> order('update_time', 'desc');
      }
    }else{
      $item -> order('update_time', 'desc');
    }
    $res = $item -> select();
    if(count($res) === 0){  //未检索到数据
      return json(['err' => '未能检索到数据']);
    }
    //获取总数
    $total = Item::where('school',$req['school'])
                   ->where('title', 'like', getQueryString($req['keyword']))
                   ->count();
    return json(['data' => $res, 'total' => $total]);
  }
  
  //首页分类
  public function category(){
    $req = input();
    //验证字段
    $reqV = $this -> validate($req, 'item.category');
    if($reqV !== true){
      return json(['err' => $reqV]);
    }
    //查询条数
    $count = Item::where('school', $req['school']);
    if(isset($req['cate'])){
      $count -> where('cate', $req['cate']);
    }
    $count = $count -> count();
    if($count === 0){
      return json(['err' => '未能检索到数据']);
    }
    //查询数据
    $item = Item::where('school', $req['school']);
    if(isset($req['cate'])){
      $item -> where('cate', $req['cate']);
    }
    $res = $item -> page($req['page'], $req['size'])
                 -> order('update_time','desc')
                 -> select();
    return json(['data' => $res, 'count' => $count]);
  }
  
  //点击商品链接
  public function find(){
    if(!input('?id')){
      return json(['err' => '没有ID字段'],400);
    }
    $id = input('id');
    $item = Item::where('id', $id) -> find();
    if($item){
      $res = obj2arr($item);
      $res['nickname'] = $item -> user -> nickname;
    }else{
      return json(['err' => '物品不存在!']);
    }
    return json($res);
  }
  
  //列出指定用户的所有商品
  public function listItem(){
    if(!decodeToken()){
      return json([],401);
    }
    if(!input('?user_id')){
      return json(['err' => 'api参数错误']);
    }
    $userId = input('user_id');
    $res = Item::where('user_id', $userId)->order('create_time', 'desc')->select();
    return json($res);
  }
  
}