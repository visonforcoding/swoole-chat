<?php

//准备数据用
require_once 'function.php';
$avatar_path = dirname(__FILE__) . '/resource/avatar';
//$avatars = directory_tree($avatar_path);
$userinfo = [];

$redis_host = '192.168.33.10';
$redis_port = '6379';
$redis = new \Redis();
$redis->connect($redis_host,$redis_port);
$avatars = file_get_contents('userinfo.json');
var_dump(json_decode($avatars));
foreach(json_decode($avatars) as $avatar){
    $redis->sAdd('chat_userinfo',  json_encode($info));
}
//foreach ($avatars as $avatar) {
//    if (preg_match('/120px\-(.*)\./', $avatar['name'], $matches)) {
//        $name = $matches[1];
//    } else {
//        preg_match('/(.*)\./', $avatar['name'], $matches);
//        $name = $matches[1];
//    }
//    $info = [
//        'nick' => $name,
//        'avatar' => $avatar['name']
//    ];
//    $userinfo[] = $info;
//    $redis->sAdd('chat_userinfo',  json_encode($info));
//}
//file_put_contents('userinfo.json',  json_encode($userinfo));

