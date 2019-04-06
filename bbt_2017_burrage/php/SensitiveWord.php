<?php
header("content-Type: text/html; charset=utf-8");
class SensitiveWord{
   private $dict;
   private $dict_path;

   public function __construct($dict_path){
      $this->dict = array();
      $this->dict_path = $dict_path;
      $this->initDict();
   }
   //初始化敏感词库
   private function initDict(){
      $handle = fopen($this->dict_path, 'r');
      if(!$handle){ 
         throw new RuntimeException('open file error');
      }

      while(!feof($handle)){
         $word = trim(fgets($handle));
          if(empty($word)){
            continue;
         }

         $make_dict = &$this->dict;

         $count = strlen($word);

         for($i=0;$i<$count;$i++){
            if(!isset($make_dict[$word[$i]])){
               $make_dict[$word[$i]] = array();
            }
            $make_dict = &$make_dict[$word[$i]];
         }
         $make_dict['end'] = 1;
      }

      fclose($handle);
   }
   //判断敏感词函数
   public function filter($str, $max_distance=10){
      if($max_distance<1){
         $max_distance = 1;
      }
      //echo mb_detect_encoding($str, "auto")."\n";
      $str = trim($str);
      $count = strlen($str);
      for($i=0;$i<$count;$i++){
         if(isset($this->dict[$str[$i]])){
            $check_dict = &$this->dict[$str[$i]]; 
            //敏感词位置数组 
            $index = array(); 
            //记录敏感词位置
            for($j=$i+1, $d=0; $d<$max_distance && $j<$count; $j++,$d++){
               if(isset($check_dict[$str[$j]])){
                  $index[] = $j;
                  $check_dict =  &$check_dict[$str[$j]];
                  $d = -1;
               }
            }
            //替换敏感词
            if(isset($check_dict['end'])){
               $str[$i] = '*';
               foreach($index as $a){
                  if($a - $i = 1){
                     $i = $a;
                  }
                  $str[$a] = '*';
               }
            }
         }
      }
      return $str;
   }
}
