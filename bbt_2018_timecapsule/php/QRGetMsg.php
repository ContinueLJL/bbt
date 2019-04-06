<?php
include_once("conn.php");

function getmsg($uid){
   global $link;
   $stmt = $link->prepare("SELECT * FROM qrcode_user WHERE user_id=?");
   $stmt->bind_param("s", $uid);
   $stmt->execute();
   $result = $stmt->get_result();
   if($result->num_rows == 1){
      $row = $result->fetch_assoc();
      $data = [
         "status"    => 0,
         "receiver"  => $row['receiver'],
      ];
      echo json_encode($data);
   }else echo json_encode(["status" => 2]);
}

if(strlen($_POST['id'])==8){
   getmsg($_POST['id']);
}else echo json_encode(["status" => 2]);
