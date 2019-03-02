<?php
function _templates() {
  require_login('Admin');    
  $data['pagename']='Email-templates ';
  $view = new View();
  $data['view'] = $view;
  
  $user = new User();
  $data['body'][]=View::do_fetch(VIEW_PATH.'templates/add.php',$data);
  View::do_dump(VIEW_PATH.'layouts/layout_admin.php',$data);
  }
  ?>