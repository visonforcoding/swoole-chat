<?php

/**
 * Encoding     :   UTF-8
 * Created on   :   2016-9-18 12:19:55 by caowenpeng , caowenpeng1990@126.com
 */
$serv = new swoole_websocket_server("0.0.0.0", 9502);
//$serv->set(array('daemonize' => true));   //以守护进程运行
$serv->on('Open', function($server, $req) {
    echo "connection open: " . $req->fd;
    echo "connection counts: " . count($server->connections)."\r\n";
});

$serv->on('Message', function($server, $frame) {
    echo "message: " . $frame->data . "\r\n";
//    $server->push($frame->fd, json_encode(["hello", "world"]));
    var_dump($server->connections);
    foreach ($server->connections as $fd) {
        $server->push($fd, json_encode($frame->data,JSON_UNESCAPED_UNICODE));
    }
});

$serv->on('Close', function($server, $fd) {
    echo "connection close: " . $fd."\r\n";
    echo "connection counts: " . count($server->connections)."\r\n";
});

$serv->start();
