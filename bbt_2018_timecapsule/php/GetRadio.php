<?php

class GetRadio{
   private $media_id, $user_id, $file_url;

   public function __construct($server_id, $url){
      $this->media_id = $server_id;
      $this->file_url = $url;
   }

   private function getMedia(){
      $url = "https://hemc.100steps.net/2017/wechat/Home/Public/getMedia?media_id=".$this->media_id;
      try {
         $curl = curl_init();
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($curl, CURLOPT_TIMEOUT, 500);
         curl_setopt($curl, CURLOPT_URL, $url);

         $res = curl_exec($curl);
         curl_close($curl);

         return $res;
      } catch (Exception $e) {
         echo json_encode(["status" => 3]);
         exit;
      }
   }

   private function parseRadio(){
      $res = json_decode($this->getMedia());
      $res = (array)$res;
      if ($res['status'] == 0){
         $data = base64_decode($res['data']);
         return $data;
      } else{
         echo json_encode(["status" => 3]);
         exit;
      }
   }

   public function saveRadio(){
      $data = $this->parseRadio();
      if(file_put_contents($this->file_url, base64_decode($data))){
         return true;
      }else{
         echo json_encode(["status" => 3]);
         exit;
      }
   }
}
