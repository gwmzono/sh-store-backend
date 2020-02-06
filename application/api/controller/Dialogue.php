<?php

namespace app\api\controller;

use think\Controller;
use app\api\model\Dialogue as DM;

class Dialogue extends Controller
{
  public function readList(){
    if(!decodeToken()){
      return json([],401);
    }
    $req = input();
    //查询对应id的对话
    $dialogueM = DM::where(function($query) use($req){
      $query->where('from',$req['user_id'])->where('from_visible',1);
    })->whereOr(function($query) use($req){
      $query->where('to',$req['user_id'])->where('to_visible',1);
    })->field('from_visible,to_visible', true)->order('create_time')->select();
    $res = [];
    foreach($dialogueM as $key=>$value){
      $res[$key] = obj2arr($value);
      $res[$key]['item_title'] = $value->item->title;
      if($value->from === (int)$req['user_id']){
        $res[$key]['nickname'] = $value->toUser->nickname;
      }else{
        $res[$key]['nickname'] = $value->fromUser->nickname;
      }
    }
    return json($res);
  }
  
  public function hideDialogue(){
    if(!decodeToken()){
      return json([],401);
    }
    $req = input();
    $dMList1 = DM::where('item_id', $req['item_id'])
               ->where('from', $req['user_id'])
               ->select();
    foreach($dMList1 as $value){
      $value -> from_visible = 0;
      $value -> save();
    }
    $dMList2 = DM::where('item_id', $req['item_id'])
               ->where('to', $req['user_id'])
               ->select();
    foreach($dMList2 as $value){
      $value -> to_visible = 0;
      $value -> save();
    }
    return json(['err' => false]);
  }
  
  public function send(){
    if(!decodeToken()){
      return json([],401);
    }
    $req = input();
    $dialogueM = DM::create($req);
    if($dialogueM -> getError()){
      return json(['err' => $dialogueM -> getError()]);
    }
    return json(['err' => false]);
  }
  
  
  public function deleteMsg(){
    if(!decodeToken()){
      return json([],401);
    }
    $id = input('id');
    $dialogueM = DM::get($id);
    if($dialogueM -> delete()){
      return json(['err' => false]);
    }else{
      return json(['err' => '删除失败!']);
    }
  }
  
}