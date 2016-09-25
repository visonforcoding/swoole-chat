<?php

/**
 * Encoding     :   UTF-8
 * Created on   :   2016-9-18 12:19:55 by caowenpeng , caowenpeng1990@126.com
 */
$serv = new swoole_websocket_server("0.0.0.0", 9502);
//$serv->set(array('daemonize' => true));   //以守护进程运行'
//配置区

$redis_host = '192.168.33.10';
$redis_port = '6379';
$redis = new \Redis();
$redis->connect($redis_host, $redis_port);

$room_admin = [
    'nick' => '曹麦穗',
    'avatar' => 'avatar_1.jpg'
];
//每次启动 server 初始化数据  还原用户库 清空聊天室用户
$avatars = file_get_contents('userinfo.json');
foreach(json_decode($avatars) as $avatar){
    $redis->sAdd('chat_userinfo',  json_encode($avatar));
}
$redis->del('chat_room_user');
$serv->on('Open', function($server, $req)use($redis,$room_admin) {
    //$req 对象是 server_http_request  $req->fd 属性 为客户端请求id 此id可用作push的发送对象
    $user = $redis->sPop('chat_userinfo');  //从昵称库中随机找一个用户 并删除
    $userinfo = json_decode($user);
    $redis->hSet('chat_room_user', $req->fd, $user);  //加入到聊天室
    $data = [
        'body' => '欢迎'.$userinfo->nick.'加入群聊!',
        'nick' => $room_admin['nick'],
        'avatar' => $room_admin['avatar'],
        'timestamp' => time(),
        'unique' => md5(uniqid()),
    ];
    foreach ($server->connections as $fd) {
        $server->push($fd, json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    //echo "connection open: " . $req->fd;
    //echo "connection counts: " . count($server->connections) . "\r\n";
});

$serv->on('Message', function($server, $frame)use($redis) {
    echo "message: " . $frame->data . "\r\n";
    
    foreach ($server->connections as $fd) {
        $user = $redis->hGet('chat_room_user',$frame->fd);
        $userinfo = json_decode($user);
        $data = [
            'body' => $frame->data,
            'nick' => $userinfo->nick,
            'avatar' => $userinfo->avatar,
            'timestamp' => time(),
            'unique' => md5(uniqid()),
        ];
        $server->push($fd, json_encode($data, JSON_UNESCAPED_UNICODE));
    }
});

$serv->on('Close', function($server, $fd) {
    echo "connection close: " . $fd . "\r\n";
    echo "connection counts: " . count($server->connections) . "\r\n";
});

$serv->start();
