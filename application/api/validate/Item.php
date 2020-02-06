<?php

namespace app\api\validate;

use think\Validate;

class Item extends Validate
{
  protected $rule = [
    'cate' => 'require|chs',
    'title' => 'require|length:4,30',
    'desc' => 'require|length:10,60000',
    'price' => 'require',
    'school' => 'require|chs|length:4,30',
    'keyword' => 'require|max:30',
    'page' => 'require|number',
    'size' => 'require|number',
  ];
  protected $scene = [
    'publish' => ['cate','title','desc','price'],
    'search' => ['school', 'keyword', 'page', 'size'],
    'category' => ['school', 'page', 'size'],
  ];
}