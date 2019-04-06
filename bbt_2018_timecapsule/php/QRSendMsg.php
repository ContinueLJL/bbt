<?php
error_reporting(E_ALL^E_NOTICE);
include_once("conn.php");
require_once("GetRadio.php");

class QRCodeMsg{
   private $send_time, $uid, $contents,$half_year,$one_year,$half_video,$one_video, $media_id;

   public function __construct($data){
      $this->send_time = $data['send_time'];
      $this->uid = strval($data['uid']);
      $this->contents = htmlspecialchars($data['content']);
      $this->getDB();
      if(isset($data['server_id']))
         $this->media_id = $data['server_id'];
   } 
   
   public function getDB(){
      global $link;
      $stmt = $link->prepare("SELECT * FROM qrcode_user WHERE user_id=?");
      $stmt->bind_param("s", $this->uid);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows==1){
         $row = $result->fetch_assoc();
         $this->half_year = $this->send_time == "半年" ? $row['half_year_num']+1 : $row['half_year_num'];
         $this->one_year = $this->send_time == "一年" ? $row['one_year_num']+1 : $row['one_year_num'];
         $this->half_video = $row['half_video_num'];
         $this->one_video = $row['one_video_num'];
      }else {
         echo json_encode(["status" => 2]);
         exit;
      }
   }
   
   public function updateVideoNum(){
      if($this->send_time == "半年")
         $this->half_video++;
      else $this->one_video++;
   }

   public function updateUserDB(){
      global $link;
      $stmt = $link->prepare("UPDATE qrcode_user SET half_year_num=?, one_year_num=?, half_video_num=?, one_video_num=? WHERE user_id=?");
      $stmt->bind_param("iiiis", $this->half_year, $this->one_year, $this->half_video, $this->one_video, $this->uid);
      $query = $stmt->execute();
      if($query)
         echo json_encode(["status" => 0]);
      else{
         echo json_encode(["status" => 3]);
         exit;
      }
   }

   public function saveMsg(){
      global $link;
      $stmt = $link->prepare("INSERT INTO qrcode_msg (user_id, send_time, content) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $this->uid, $this->send_time, $this->contents);
      $query = $stmt->execute();
      if($query) return true;
      else return false;
   }

   //public function saveVideo(){
      //if($_FILES['video']['error']==0){
         //$this->updateVideoNum();
         //$tmp_name = $_FILES["video"]["tmp_name"];
         //$num = strval($this->send_time=="半年" ? $this->half_video : $this->one_video);
         //if(move_uploaded_file($tmp_name, "../QR_video/".$this->uid.$this->send_time.$num.".mp3"))
            //return true;
         //return false;
      //}else{
         //echo json_encode(["status" => 4]);
         //exit;
      //}
   /*}*/


   public function saveRadio(){
      $this->updateVideoNum();
      $num = strval($this->send_time=="半年" ? $this->half_video : $this->one_video);
      $time = ($this->send_time=="半年"?"half":"one");
      $url = "../QR_video/".$this->uid.$time.$num.".amr";
      $radio = new GetRadio($this->media_id, $url);
      if($radio->saveRadio())
         if($this->saveMsg())
            return true;
      else
         $this->echoMsg(3);
   }
}

function check_msg($data){
   if(!($data['send_time']=="半年" || $data['send_time']=="一年"))
      return false;
   if(!(strlen($data['uid'])==8))
      return false;
   if(!(mb_strlen($data['content'],'utf8')<20000))
      return false;
   return true;
}

if($_POST['uid']){
   if(check_msg($_POST)){
      $qrsend = new QRCodeMsg($_POST);
      if(isset($_POST['server_id'])){
         if($qrsend->saveRadio())
            $qrsend->updateUserDB();
      }else{
         if($qrsend->saveMsg())
            $qrsend->updateUserDB();
      }
   }else echo json_encode(["status" => 2]);
}
