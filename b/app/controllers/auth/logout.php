<?php
function _logout() {
  unset($_SESSION['authuid']);
  unset($_SESSION['authname']);
  unset($_SESSION['authprivilage']);
  session_destroy();
  redirect('main/login','You have logged out!');
}