<?php
error_reporting(E_ALL^E_NOTICE);
include_once("conn.php");
require_once("GetRadio.php");
session_start();

class OnlineMsg{
   private $receiver, $phone, $send_time, $contents, $code, $media_id, $openid;
   
   public function __construct($data, $openid){
      $this->receiver = $data['receiver'];
      $this->phone = strval($data['phone']);
      $this->send_time = $data['send_time'];
      $this->contents = htmlspecialchars($data['content'], ENT_QUOTES);
      $this->getCode();
      $this->openid = $openid;
      if(isset($data['server_id']))
         $this->media_id = $data['server_id'];
      /*$fp = fopen("./code.txt", "r");*/
      //$this->code = trim(fgets($fp), "\n");
      //ob_start();
      //fpassthru($fp);
      //fclose($fp);
      //file_put_contents("./code.txt", ob_get_clean());      
   }

   public function getCode(){
      global $link;
      $stmt = $link->prepare("SELECT * FROM online_code WHERE used=? LIMIT 1");
      $num = 0;
      $stmt->bind_param("i", $num);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      if($result->num_rows==1){
         $row = $result->fetch_assoc();
         $this->code = $row['code'];
         $stmt = $link->prepare("UPDATE online_code SET used=1 WHERE code=?");
         $stmt->bind_param("s", $this->code);
         $query = $stmt->execute();
         if(!$query){
            $this->echoMsg(404);
            exit;
         }
      }else {
         $this->echoMsg(404);
         exit;
      }
   }

   public function echoMsg($i){
      if($i==0)  echo json_encode(["status" => 0, "code" => $this->code]);
      else       echo json_encode(["status" => $i]);
   }

   public function saveMsg(){
      global $link;
      $stmt = $link->prepare("INSERT INTO online_msg (receiver, phone, send_time, content, code, openid) VALUES (?,?,?,?,?,?)");
      $stmt->bind_param("ssssss", $this->receiver, $this->phone, $this->send_time, $this->contents, $this->code, $this->openid);
      $query = $stmt->execute();
      if($query) $this->echoMsg(0);
      else $this->echoMsg(3);
      $stmt->close();
   }
   
/*   public function saveVideo(){*/
      //if($_FILES["video"]["error"]==0){
         //$tmp_name = $_FILES["video"]["tmp_name"];
         //if(move_uploaded_file($tmp_name, "../video/".$this->code.".mp3"))
            //$this->saveMsg();
         //else
            //$this->echoMsg(3);
      //}else $this->echoMsg(4);
   //}
   public function saveRadio(){
      $url = "../video/".$this->code.".amr";
      $radio = new GetRadio($this->media_id, $url);
      if($radio->saveRadio())
         $this->saveMsg();
      else
         $this->echoMsg(3);
   }
}

function check_msg(){
   if(!(mb_strlen($_POST['receiver'],'utf8') < 30))
      return false;
   if(!(mb_strlen($_POST['phone'],'utf8') == 11))
      return false;
   if(!($_POST['send_time']=="半年" || $_POST['send_time']=="一年"))
      return false;
   if(!(mb_strlen($_POST['content'])<20000))
      return false;
   return true;
}

if($_POST){
   if(check_msg()){
      $online = new OnlineMsg($_POST, $_SESSION['openid']);
      //if($_FILES['video']){
      //$online->saveVideo();
      if(isset($_POST['server_id'])){
         $online->saveRadio();
      }else{
         $online->saveMsg();
      }
   }else echo json_encode(["status" => 2]);
}
