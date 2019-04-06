<?php

class Logout{
   session_start();

   unset($_SESSION['uid'];
   unset($_SESSION['username'];

}

if($_GET['action'] == 'logout'){
   $logout = new Logout();
}
