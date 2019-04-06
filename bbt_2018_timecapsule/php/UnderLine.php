<?php
error_reporting(E_ALL^E_NOTICE);
include_once("conn.php");
session_start();

class UnderLine {
   private $sender_name,$sender_phone,$code,$receiver,$receiver_phone,$addr,$send_time,$isseal,$openid;
   
   public function __construct($data, $openid){
      $this->sender_name = $data['sender_name'];
      $this->sender_phone = strval($data['sender_phone']);
      $this->code = $data['code'];
      $this->receiver = $data['receiver'];
      $this->receiver_phone = strval($data['receiver_phone']);
      $this->addr = $data['addr'];
      $this->send_time = $data['send_time'];
      $this->isseal = $data['isseal'];
      $this->openid = $openid;
   }

   public function echoMsg($i){
      if($i==0){
         echo json_encode([
            "status" => 0
         ]);
      }else{
         echo json_encode([
            "status" => $i,
         ]);
      }
   }

   public function saveMsg(){
      global $link;
      $stmt = $link->prepare("INSERT INTO under_line (sender_name, sender_phone, code, receiver, receiver_phone, addr, send_time, isseal, openid) VALUES (?,?,?,?,?,?,?,?,?)");
      $stmt->bind_param("sssssssss", $this->sender_name, $this->sender_phone, $this->code, $this->receiver, $this->receiver_phone, $this->addr, $this->send_time, $this->isseal, $this->openid);
      $query = $stmt->execute();

      if($query) $this->echoMsg(0);
      else $this->echoMsg(3);

      $stmt->close();
   }

   public function isExist(){
      global $link;
      $stmt = $link->prepare("SELECT * FROM under_line WHERE code=?");
      $stmt->bind_param("s", $this->code);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows==1){
         echo json_encode(["status" => 5]);
         exit;
      }

      $st = $link->prepare("SELECT * FROM under_code WHERE code=?");
      $st->bind_param("s", $this->code);
      $st->execute();
      $res = $st->get_result();
      if($res->num_rows==0){
         echo json_encode(["status" => 6]);
         exit;
      }
   }
}

function check_msg(){
   if(!(mb_strlen($_POST['sender_name'],'utf8') < 30))
      return false;
   if(!(strlen($_POST['sender_phone']) == 11))
      return false;
   if(!(strlen($_POST['code'])==8))
      return false;
   if(!(mb_strlen($_POST['receiver'],'utf8') < 30))
      return false;
   if(!(strlen($_POST['receiver_phone']) == 11))
      return false;
   if(!($_POST['send_time']=="半年" || $_POST['send_time']=="一年"))
      return false;
   if(!(mb_strlen($_POST['addr'], 'utf8')<81))
      return false;
   if(!($_POST['isseal']=='Y' || $_POST['isseal']=='N'))
      return false;
   return true;
}

if($_POST){
   if(check_msg()){
      $under = new UnderLine($_POST, $_SESSION['openid']);
      $under->isExist();
      $under->saveMsg();
   }else echo json_encode(["status" => 2]);
}
