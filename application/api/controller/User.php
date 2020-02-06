<?php

namespace app\api\controller;

use think\Controller;
use app\api\model\User as UserM;
use jwt\JWT;

class User extends Controller
{ 
  public function userValidate(){
    $req = input();
    $user = new UserM;
    if(isset($req['phone'])){
      if($user -> where('phone', $req['phone']) -> find()){
        return json(['err' => '该手机号已被注册']);
      }
    }elseif(isset($req['nickname'])){
      if($req['nickname'] === ''){
        return json(['err' => false]);
      }elseif($user -> where('nickname', $req['nickname']) -> find()){
        return json(['err' => '该昵称已被注册']);
      }
    }
    return json(['err' => false]);
  }

  public function register(){
    $req = input();
    //没有昵称时自动使用手机号做昵称
    $req['nickname'] = $req['nickname'] ?
                       $req['nickname'] : $req['phone'];
    //验证字段
    $vRes = $this->validate($req, 'User.register');
    if($vRes !== true){
      return json(['err' => $vRes]);
    }
    //是否存在
    $user = new UserM;
    if($user -> where('phone', $req['phone']) -> find()){
      return json(['err'=>'手机号已被注册!']);
    }elseif($user -> where('nickname', $req['nickname']) -> find()){
      return json(['err'=>'昵称已经存在!']);
    }
    //存入
    $userM = UserM::create($req);
    if($userM -> getError()){
      return json(['err' => $userM -> getError()]);
    }else{
      return json([
        'err' => false,
      ]);
    }
  }
  
  public function login(){
    $token = request() -> header('x-token');
    if($token){
      if(decodeToken()){  //有token且正确
        return json([]);
      }else{  //有token但错误
        return json([],401);
      }
    }
    
    $req = input();
    //验证字段
    $vRes = $this->validate($req, 'User.login');
    if($vRes !== true){
      return json(['err' => $vRes]);
    }
    //验证用户
    $user = UserM::where('phone', $req['phone']) -> find();
    if($user === null){
      return json(['err'=>'该手机未注册!']);
    }
    if($user -> status === -1){
      return json(['err' => '该账号已被冻结!']);
    }    
    //验证密码
    $pass = $user -> password;
    if(!password_verify($req['password'], $pass)){
      return json(['err' => '密码错误!']);
    }
    //写入状态
    $payload = [
      'id' => $user->id,
      'phone' => $user->phone,
      'nickname' => $user->nickname,
      'status' => $user->status,
      'school' => $user->school,
      'create_at' => time(),
      'expire_at' => time()+24*60*60, //一天
    ];
    $jwt = JWT::encode($payload, getSalt());
    return json(['err'=>false], 200, ['x-token'=>$jwt]);
  }
  
  public function changePassword(){
    if(!decodeToken()){
      return json([],401);
    }
    $req = input();
    $user = UserM::get($req['id']);
    if(!password_verify($req['origin'], $user -> password)){
      return json(['err' => '密码错误!']);
    }
    $user -> password = $req['password'];
    if($user -> save()){
      return json(['err' => false]);
    }
  }
  
  public function deleteUser(){
    $userInfo = decodeToken();
    if(!decodeToken()){
      return json([],401);
    }
    $req = input();
    // 若token中的id与操作id不符,则返回401
    if($req['id'] !== $userInfo->id){
      return json([],401);
    }
    $user = UserM::get($req['id']);
    $itemList = $user -> item() -> where('user_id',$user -> id) -> select();
    $picList = [];
    //获得所有item的图片信息
    foreach($itemList as $item){
      $pic_t = trim($item->pic," []");
      $pic_l = str_getcsv($pic_t);
      foreach($pic_l as $pic){
        array_push($picList,$pic);
      }
    }
    //逐个删除图片
    foreach($picList as $pic){
      unlink(serverPicRoot().$pic);
    }
    //删除物品和用户
    $user -> item() -> where('user_id',$user -> id) -> delete();
    $user -> delete();
    return json(['err' => false]);
  }
}