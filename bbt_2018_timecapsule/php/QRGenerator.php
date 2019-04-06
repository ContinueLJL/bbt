<?php
error_reporting(E_ALL^E_NOTICE);
require './vendor/autoload.php';

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Intervention\Image\ImageManagerStatic as Image;
class QRGenerator{
   private $url, $ordinaryImg, $id, $rand;
   public function __construct($uid){
      $this->id = strval($uid);
      $this->url = "https://hemc.100steps.net/2018/time-capsule/online.html?ID=".$this->id;
      $this->rand = (mt_rand(1,100)%2);
      $this->ordinaryImg = ($this->rand==0?"./img/capsule.jpg":"./img/capsule1.jpg");
   }
   public function QrGenerator(){
      
      $img = Image::make($this->ordinaryImg);
      $img->resize(1338, 1890);
      $qr = new QrCode($this->url);
      if($this->rand == 0){
         $syImg = $qr->setWriterByName('png')
                     ->setSize(335)
                     ->setMargin(0)
                     ->setEncoding('UTF-8')
                     ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)
                     ->setForegroundColor(['r' => 158, 'g' => 197, 'b' => 197, 'a' => 0])
                     ->setBackgroundColor(['r' => 254, 'g' => 249, 'b' => 245, 'a' => 0])
                     ->writeString();

         $img->insert($syImg, 'bottom-right', 75, 495);
         $img->insert("./img/logo.jpg", 'top-left', 0, 0);
      }else{
         $syImg = $qr->setWriterByName('png')
                     ->setSize(347)
                     ->setMargin(0)
                     ->setEncoding('UTF-8')
                     ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)
                     ->setForegroundColor(['r' => 167, 'g' => 205, 'b' => 205, 'a' => 0])
                     ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0])
                     ->writeString();

         $img->insert($syImg, 'bottom-right', 486, 315);
      }
      $result = $img->save('../qrcode_img/'.$this->id.'.jpg', 100);
      if($result){

         echo json_encode([
            "status" => 0,
            "url"    => "https://hemc.100steps.net/2018/time-capsule/qrcode_img/".$this->id.".jpg",
            "id"     => $this->id
         ]);
      }
      else{
         echo json_encode(["status" => 3]);
         exit;
      }
   }
}
