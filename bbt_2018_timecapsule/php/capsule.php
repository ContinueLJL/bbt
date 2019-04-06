<?php
require './vendor/autoload.php';

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Intervention\Image\ImageManagerStatic as Image;
class QRGenerator{
   private $url, $ordinaryImg, $id;
   public function __construct($uid){
      $this->id = strval($uid);
      $this->url = "https://www.caibf.cn/online.html?ID=".$this->id;
      $this->ordinaryImg = "./timecapsule.jpg";
   }
   public function QrGenerator(){
      
      $img = Image::make($this->ordinaryImg);
      
      $qr = new QrCode($this->url);
      $syImg = $qr->setWriterByName('png')
                  ->setSize(220)
                  ->setMargin(0)
                  ->setEncoding('UTF-8')
                  ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)
                  ->setForegroundColor(['r' => 158, 'g' => 197, 'b' => 197, 'a' => 0])
                  ->setBackgroundColor(['r' => 254, 'g' => 249, 'b' => 245, 'a' => 0])
                  ->writeString();
      
      $img->insert($syImg, 'bottom-right', 52, 332);
      //$result = $img->save('../qrcode_img/'.$this->id.'.jpg', 90);
      //if($result){
         echo $img->response();
         /*echo json_encode([*/
            //"status" => 0,
            //"url"      => "http://localhost/bbt_timecapsule/qrcode_img/".$this->id.".jpg"
         /*]);*/
      //}
      //else{
         //echo json_encode(["status" => 3]);
         //exit;
      //}
   }
}

if($_POST){
   $qr = new QRGenerator("11111111");
   $qr->QrGenerator();
}
