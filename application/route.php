<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
  '__pattern__' => [
      'name' => '\w+',
  ],
  '[hello]'     => [
      ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
      ':name' => ['index/hello', ['method' => 'post']],
  ],
  
  'test' => 'api/Test/index',
  
  'city' => 'api/school/selectCity',
  'school' => 'api/school/selectSchool',
  'schoolName' => 'api/school/searchSchool',
  
  'validate' => 'api/user/userValidate',
  'register' => 'api/user/register',
  'login' => 'api/user/login',
  'changePassword' => 'api/User/changePassword',
  'unRegister' => 'api/User/deleteUser',
  
  'upload' => 'api/Upload/upload',
  'deleteUp' => 'api/Upload/deleteup',
  'publish' => 'api/Publish/index',
  'deleteItem' => 'api/Publish/deleteItem',
  'editItem' => 'api/Publish/editItem',
  
  'search' => 'api/Search/index',
  'category' => 'api/Search/category',
  'item' => 'api/Search/find',
  'allItem' => 'api/Search/listItem',
  
  'dialogue' => 'api/Dialogue/readList',
  'hideDialogue' => 'api/Dialogue/hideDialogue',
  'sendMessage' => 'api/Dialogue/send',
  'deleteMessage' => 'api/Dialogue/deleteMsg',
];
