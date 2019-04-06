<?php
include_once("config.php");

header('Content-type: application/json');
$link = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
if($link->connect_error){
   echo json_encode([
      'status' => 2,
      'msg'    => '数据请求失败，请稍后再试',
   ]);
   exit;
}

if(!$link->set_charset('utf8mb4')){
   echo json_encode([
      'status' => 2,
      'msg'    => '数据请求异常，请稍后再试',
   ]);
}
