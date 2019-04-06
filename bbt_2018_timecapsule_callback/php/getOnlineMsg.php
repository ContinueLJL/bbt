<?php

include_once("conn.php");
//$_SESSION['openid'] = "dddd";
if($_SERVER['REQUEST_METHOD'] == 'POST'){

   if(!isset($_SESSION['openid'])){
      echo json_encode([
         'status' => 1,
         'msg'    => '微信未授权'
      ]);
      exit;
   }

   if(time() < START_TIME){
      echo json_encode([
         'status' => 3,
         'msg'    => '活动还未开始，敬请期待'
      ]);
      exit;
   }

   if(time() > END_TIME){
      echo json_encode([
         'status' => 3,
         'msg'    => '活动已经结束， 感谢关注'
      ]);
      exit;
   }

   $code = htmlspecialchars($_POST['code']);
   $stmt = $link->prepare("SELECT content FROM online_msg WHERE code=?");
   $stmt->bind_param("s",  $code);
   $stmt->execute();

   $data = [];
   $radio = [];
   $result = $stmt->get_result();
   if($result->num_rows > 0){
      $row = $result->fetch_assoc();
      if($row['content'] == "" || $row['content'] == null){
         $file_url = ONLINEPATH.$code.".mp3";
         if(file_exists($file_url)){
            $temp = file_get_contents($file_url);
            $radio[] = base64_encode($temp);
         }
         else{
            echo json_encode([
               'status' => 4,
               'msg'    => '编码异常，无法查询'
            ]);
            $stmt->close();
            exit;
         }
      }
      else{
         $data[] = htmlspecialchars_decode($row['content']);
      }
      echo json_encode([
         'status' => 0,
         'data'   => $data,
         'radio'  => $radio,
      ]);
   }
   else{
      echo json_encode([
         'status' => 5,
         'msg'    => '不存在该编码，请查看是否填错～'
      ]);
   }
   $stmt->close();
}
