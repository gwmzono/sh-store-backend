<?php

namespace app\api\controller;

use think\Controller;

class Upload extends Controller
{
  function upload(){
    $file = request()->file('image');
    if($file){
      $info = $file->move(serverPicRoot());
      if($info){
        return json(['err' => false, 'path' => $info->getSaveName()]);
      }else{
        return json(['err' => $file->getError()]);
      }
    }else{
      return json(['err' => 'no img specified']);
    }
  }
  
  //删除已上传图片
  function deleteUp(){
    if(!decodeToken()){
      return json([],401);
    }
    if(input('?file')){
      $file = serverPicRoot().input('file');
    }else{ return json(['err' => '未指定文件']); }
    
    if(unlink($file)){
      return json(['err' => false]);
    }else{
      return json(['err' => '删除文件失败']);
    }

  }
}