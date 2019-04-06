<?php

$link = new mysqli('localhost', 'USER', 'PASSWORD', 'DBNAME');

if($link->connect_error){
   die("连接数据库失败".mysqli_error());
}
