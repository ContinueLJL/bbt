<?php
error_reporting(E_ALL^E_NOTICE);
include_once("conn.php");
session_start();
class CheckQRCode{
   private $row, $id;
   public function isExist($openid){
      global $link;
      $stmt = $link->prepare("SELECT * FROM qrcode_user WHERE openid=?");
      $stmt->bind_param("s", $openid);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      if($result->num_rows == 1){
         $this->row = $result->fetch_assoc();
         $this->id = $this->row['user_id'];
         return true;
      }else 
         return false;
   }

   public function echoMsg($type){
      if($type==1){
         echo json_encode([
            "status" => 0,
            "url"    => "https://hemc.100steps.net/2018/time-capsule/qrcode_img/".$this->id.".jpg",
            "id"     => $this->id
         ]);
      }elseif($type==2){
         echo json_encode([
            "status" => 0,
            "half_num" => $this->row['half_year_num'],
            "year_num" => $this->row['one_year_num']
         ]);
      }else echo json_encode(["status" => 404]);
   }
}

if($_POST['check']==1){
   // type＝１为生成二维码页面查询，２为收件箱页面查询
   $type = $_POST['type'];
   $check = new CheckQRCode();
   if($check->isExist($_SESSION['openid'])) 
      $check->echoMsg($type);
   else 
      echo json_encode(["status" => 1]);
}
