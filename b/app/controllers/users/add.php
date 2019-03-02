<?php
function _add() {
  require_login('Admin');    
  $data['pagename']='Users-Add ';
  $view = new View();
  $data['view'] = $view;
  $user = new User();
  $data["users"]	= $users = $user->retrieve_many();
  $roles=new Roles();
  $data['roles']=$roles=$roles->retrieve_many();
  
  $data['foot'][]="<script src='".myUrl('js/pages/users-add.js')."'></script>";
  $data['body'][]=View::do_fetch(VIEW_PATH.'users/add.php',$data);
  View::do_dump(VIEW_PATH.'layouts/layout_admin.php',$data);
  }
  ?>