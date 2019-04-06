<?php

include_once("conn.php");
//$_SESSION['openid'] = 'dddd';

if($_SERVER['REQUEST_METHOD'] == "GET"){
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

   $res = $link->query("SELECT * FROM qrcode_user WHERE openid='".$_SESSION['openid']."'");
   if($res->num_rows > 0){
      $row = $res->fetch_assoc();
      //$radio_num = PERIOD == "半年" ? $row['half_video_num'] : $row['one_video_num'];
      //$content_num = PERIOD == "半年" ? $row['half_year_num'] : $row['one_year_num'];
      $period = PERIOD == "半年" ? 'half' : 'one';
      $radio = [];
      $data = [];
      
      $path = QRPATH.$row['user_id'].$period."*".".mp3";
       foreach (glob($path) as $filename) {
           $temp = file_get_contents($filename);
           $radio[] = base64_encode($temp);
      }
      $con = $link->query("SELECT content FROM qrcode_msg WHERE send_time='".PERIOD."' AND user_id='".$row['user_id']."'");
      if($con->num_rows > 0){
         while($r = $con->fetch_assoc()){
            $data[] = htmlspecialchars_decode($r['content']);
         }
      }
      echo json_encode([
         'status' => 0,
         'data'   => $data,
         'radio'  => $radio
      ]);

   }
   else{
      echo json_encode([
         'status' => 6,
         'msg'    => '你未参与二维码活动'
      ]);
   }
}
