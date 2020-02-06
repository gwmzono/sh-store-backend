<?php

use jwt\JWT;

const SEED = 'e51feabca3ef2d1e0a69d47a0bf03e69';

//用于加密密码的盐值
function getSalt(){
  return md5(SEED);
}

//获取服务器图片目录
function serverPicRoot(){
  return '/data/www/store.zono.pub/uploads/';
}
//重新构造查询关键词
function getQueryString($str){
  $arr = preg_split('/\s+/', trim($str));
  $query = '%';
  foreach($arr as $value){
    $query .= ($value.'%');
  }
  return $query;
}
// model对象用getData方法得到的结果是原始数据
function obj2arr($obj){
  return json_decode(json_encode($obj), true);
}

//检查token, 合法返回用户信息, 非法或过期返回false
function decodeToken(){
  $token = request()->header('x-token');
  if(is_null($token)){
    return false;
  }
  try{
    $res = JWT::decode($token, getSalt());
    if($res->expire_at < time()){
      return false;
    }
    return $res;
  }catch(Exception $e){
    return false;
  }
}