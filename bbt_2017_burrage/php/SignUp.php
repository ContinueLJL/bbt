<?php
include_once("conn.php");
class SignUp{
   private $salt;
   private $uname;
   private $pswd;
   private $phone;
   public function __construct(){
      $this->uname = $_POST['username'];
      $this->pswd = $_POST['password'];
      $this->phone = $_POST['phone'];
   }
   public function dealInfo(){
      //判断用户名及手机号为空
      if($this->uname=="" || $this->uname==null){
         $data = [
            "status" => 404,
            "msg"    => "用户名不能为空"
         ];
         echo json_encode($data);
         exit;
      }
      if($this->phone=="" || $this->phone==null){
         $data = [
            "status" => 404,
            "msg"    => "手机号不能为空"
         ];
         echo json_encode($data);
         exit;
      }
      
      //处理用户名
      if(mb_strlen($this->uname, "utf-8")>30){
         $this->uname = mb_substr($this->uname, 0, 30, 'utf-8');
      }
      $this->uname = htmlspecialchars($this->uname, ENT_QUOTES);
      // 处理手机号
      $this->phone = strval($this->phone);
      //处理密码
      $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~!@##$%%&*()_+=[]{}:,.';
      $this->salt = substr($chars, mt_rand(0, strlen($chars)-5), 5);
      $this->salt = hash("sha256", $this->salt);
      $this->pswd = $this->salt . $this->pswd;
      $this->pswd = hash("sha256", $this->pswd);
   }
   public function isExist(){
      global $link;
      $stmt = $link->prepare("SELECT * FROM user WHERE username=?");
      $stmt->bind_param("s", $this->uname);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows > 0){
         return 1;
      }else{
         return 0;
      }
   }
   public function register(){
      global $link;
      $stmt = $link->prepare("INSERT INTO user (username, phone, salt,  password) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $this->uname, $this->phone, $this->salt, $this->pswd);
      $query = $stmt->execute();
      
      $data = null;
      if($query){
         $data = [
            "status" => 0,
            "msg"    => "注册成功"
         ];
      }else{
         $data = [
            "status" => 1,
            "msg"    => "注册失败"
         ];
      }
      echo json_encode($data);
      $stmt->close();
   }
}
$reg = new SignUp();
$reg->dealInfo();
if($reg->isExist()){
   $data = [
      "status" => 2,
      "msg"    => "用户已存在"
   ];
   echo json_encode($data);
}else{
   $reg->register();
}