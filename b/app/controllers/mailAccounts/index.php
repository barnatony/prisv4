<?php
function _index($id=null) {
  require_login('Admin');
  
  $data['pagename']='MailAccounts ';
  $data['id']=$id;
  $view = new View();
  $data['view'] = $view;
  $data["mail"]= $mail = new Mail($id);
  
  $data["mails"]=$mails=$mail->retrieve_many();
  $data['foot'][]="<script src='".myUrl('js/pages/mailAccounts-index.js')."'></script>";
  $data['body'][]=View::do_fetch(VIEW_PATH.'mailAccounts/index.php',$data);
  View::do_dump(VIEW_PATH.'layouts/layout_admin.php',$data);
  }
  ?>