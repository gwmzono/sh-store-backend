<?php

namespace app\api\controller;

class Test
{
  public function index(){
    return json(input());
  }
}