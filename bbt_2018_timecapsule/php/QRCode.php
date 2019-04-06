<?php
include_once("conn.php");
require_once("QRGenerator.php");
session_start();
/*$pattern = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";*/
//$session_id = "";
//for($i=0;$i<28;$i++){
   //$session_id = $session_id.$pattern[mt_rand(0,35)];
//}
//$_SESSION['openid'] = $session_id;

class QRCode{
   private $receiver, $phone, $uid, $openid;
   public function __construct($data, $id){
      $this->receiver = $data['receiver'];
      $this->phone = strval($data['phone']);
      $this->openid = $id;
      $this->isExist();
      $this->getUid();
   }

   //　查询是否已存在该用户，避免恶意重复写入
   public function isExist(){
      global $link;
      $stmt = $link->prepare("SELECT * FROM qrcode_user WHERE openid=?");
      $stmt->bind_param("s", $this->openid);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      if($result->num_rows==1){
         echo json_encode(["status" => 2]);
         exit;
      }
   }

   public function getUid(){
      global $link;
      $stmt = $link->prepare("SELECT * FROM qr_uid WHERE used=? LIMIT 1");
      $num = 0;
      $stmt->bind_param("i", $num);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      if($result->num_rows==1){
         $row = $result->fetch_assoc();
         $this->uid = $row['uid'];
         $stmt = $link->prepare("UPDATE qr_uid SET used=1 WHERE uid=?");
         $stmt->bind_param("s", $this->uid);
         $query = $stmt->execute();
         if(!$query){
            echo json_encode(["status" => 404]);
            exit;
         }
      }else {
         echo json_encode(["status" => 404]);
         exit;
      }
   }

   public function saveMsg(){
      global $link;
      $stmt = $link->prepare("INSERT INTO qrcode_user (user_id, receiver, phone, openid) VALUES (?,?,?,?)");
      $stmt->bind_param("ssss", $this->uid, $this->receiver, $this->phone, $this->openid);
      $query = $stmt->execute();
      if($query){
         return true;
      }else {
         echo json_encode(["status" => 3]);
         exit;
      }
   }

   public function callGenerator(){
      $qr = new QRGenerator($this->uid);
      $qr->QrGenerator();

   }


}


function check_msg(){
   if(!(mb_strlen($_POST['receiver'], 'utf8') < 30))
      return false;
   if(!(mb_strlen($_POST['phone'], 'utf8')==11))
      return false;
   if(!isset($_SESSION['openid']))
      return false;
   return true;
}

if($_POST){
   if(check_msg()){
      $qrcode = new QRCode($_POST, $_SESSION['openid']);
      if($qrcode->saveMsg()){
         $qrcode->callGenerator();
      }
   }else echo json_encode(["status" => 2]);
}
