<?php
error_reporting(E_ALL^E_NOTICE);
session_start();



if($_POST['check']==1){
   if($_SESSION['openid']){
      echo json_encode([
         "status" => 0
      ]);
   } else {
      echo json_encode([
         "status" => 1
      ]);
      // 设置session,　上线后注释掉
/*      $pattern = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";*/
      //$session_id = "";
      //for($i=0;$i<28;$i++){
         //$session_id = $session_id.$pattern[mt_rand(0,35)];
      //}
      /*$_SESSION['openid'] = $session_id;*/
   }
}
