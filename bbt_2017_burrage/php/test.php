<?php

require_once('SensitiveWord.php');

$sen = new SensitiveWord(__DIR__.'/chinese_dictionary.txt');

$word = $_POST['word'];

$str = $sen->filter($word);
$data = json_encode(array("message" => $str));
echo $data;
