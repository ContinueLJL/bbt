<?php

session_start();
header('Content-type: application/json');

if(isset($_SESSION['openid'])){
   echo json_encode([
      'status' => 0,
   ]);
}else{
   echo json_encode([
      'status' => 1
   ]);
}
