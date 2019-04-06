<?php
include_once("conn.php");
function signIn(){
   $phone = $_POST['phone'];
   $pswd = $_POST['password'];
   session_start();
   global $link;
   
   //处理手机号
   $phone = strval($phone);
   if(strlen($phone) != 11){
      $data = [
         "status" => 404,
         "msg"    => "手机号码错误"
      ];
   }
   //查询数据库信息
   $stmt = $link->prepare("SELECT * FROM user WHERE phone=?");
   $stmt->bind_param("s", $phone);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = null;
   if($result->num_rows==1){
      $row = $result->fetch_assoc();
      //处理密码
      $pswd = hash("sha256", $row['salt'].$pswd);
      $user = $row['username'];
      if($pswd==$row['password']){
         $_SESSION['uid'] = $row['uid'];
         $_SESSION['username'] = $user;
         $data = [
            "status" => 0,
            "user"   => $user
         ];
      }else{
         //密码错误
         $data = [
            "status" => 1,
            "msg"    => "账号或密码错误"
         ];
      }
   }else{
      //查询不到
      $data = [
         "status"  => 1,
         "msg"     => "不存在该用户"
      ];
   }
   echo json_encode($data);
   $stmt->close();
}
signIn();