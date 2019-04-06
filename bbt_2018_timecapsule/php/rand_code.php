<?php

class randCode {
   public function rand_char(){      
      $pattern = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $key = "";
      for($i=0;$i<3;$i++){
         $key .=$pattern[mt_rand(0,25)];
      }
      return $key;
   }
   
   public function save_code($arr){
      $file = './aaa.txt';
      $f = fopen($file,'w+');
      if($f){
         file_put_contents($file, var_export($arr, true));
         fclose($f);
      }else{
         echo "open  error";
      }

   }
  
   public function unique_rand($min, $max, $num){
      $counts = 0;
      $data = array();
      while($counts < $num){
         $number = mt_rand($min,$max);
         $alph = $this->rand_char();
         $data[] = $alph.$number;
         //$data = array_flip(array_flip($data));
         $data = array_unique($data);
         $counts = count($data);
      }
      $this->save_code($data);
   }

}

$code = new randCode();
$code->unique_rand(10000,99999,10000);
