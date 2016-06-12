<?php
/**
 * Created by PhpStorm.
 * User: 强
 * Date: 2016/6/12
 * Time: 14:49
 */
include_once "function.php";
session_start();
$_SESSION["userInfo"] = array();
$server = new swoole_websocket_server("0.0.0.0", 9501);
$server->on("open",function(swoole_websocket_server $server,$request){
    $info["fd"]=$request->fd;
    $_SESSION["userInfo"][$request->fd] = $info;
    $server->push($request->fd,"欢迎進入聊天室");
});
$server->on("message",function(swoole_websocket_server $server,$frame){
    if(preg_match('/^\{(?P<name>\w*)\}$/',$frame->data,$data)){
        $username=$_SESSION["userInfo"][$frame->fd]["name"]=$data["name"];
        foreach($_SESSION["userInfo"] as $userInfo){
            if($userInfo["fd"]!=$frame->fd){
                $server->push($userInfo["fd"],$username = $_SESSION["userInfo"][$frame->fd]["name"]."，进入聊天室");
            }
        }
    }else{
        $username = $_SESSION["userInfo"][$frame->fd]["name"];
        foreach($_SESSION["userInfo"] as $userInfo){
            $server->push($userInfo["fd"],$username."说:".$frame->data);
        }
    }
});
$server->on("close",function($ser,$fd){
    unset($_SESSION["userInfo"][$fd]);
});

$server->start();