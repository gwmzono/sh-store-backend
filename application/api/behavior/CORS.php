<?php

namespace app\api\behavior;

class CORS
{
  function run(){
    header("Access-Control-Expose-Headers:x-token");
    header("Access-Control-Allow-Origin:https://store.zono.pub");
    header("Access-Control-Allow-Methods:GET, POST, PUT, DELETE, UPDATE, OPTIONS");
    header("Access-Control-Allow-Headers:Accept,Content-Type,x-token");
    if(request()->isOptions()){
      exit();
    }
  }
}