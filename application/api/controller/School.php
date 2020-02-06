<?php

namespace app\api\controller;

use think\Controller;
use app\api\model\City;
use app\api\model\HighSchool as HSM;

class School extends Controller
{
  function selectCity(){
    $req = input();
    $vRes = $this->validate($req, 'City');
    if($vRes === true){
      $alpha = strtoupper($req['alpha']);
    }else{
      return json(['err' => $vRes]);
    }
    $CityM = new City;
    $res = $CityM -> where('alpha', $alpha)
                  -> select();
    return json($res,200);
  }
  
  function selectSchool(){
    $req = input();
    $vRes = $this->validate($req, 'School.select');
    if($vRes === true){
      $city = $req['city'];
    }else{
      return json(['err' => $vRes]);
    }
    $schoolM = new HSM;
    $res = $schoolM -> where('city', 'like', $city.'%')
                    -> select();
    return json($res);
  }
  
  function searchSchool(){
    $req = input();
    $vRes = $this->validate($req, 'School.search');
    if($vRes === true){
      $name = getQueryString($req['name']);
    }else{
      return json(['err' => $vRes]);
    }
    $schoolM = new HSM;
    $res = $schoolM -> where('name', 'like', '%'.$name.'%')
                    -> select();
    return json($res);
  }
}