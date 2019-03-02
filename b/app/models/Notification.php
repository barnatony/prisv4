<?php
class Notification extends Model {

  function __construct($notification_id='') {
    parent::__construct('notification_id','notifications'); //primary key = uid; tablename = users
    $this->rs['notification_id'] = '';
    $this->rs['notification_type'] = '';
    $this->rs['name'] = '';
    $this->rs['description'] = '';
    $this->rs['created_by'] = '';
    $this->rs['created_on'] = '';
    if ($notification_id)
      $this->retrieve($notification_id);
  }

  function create() {
  	$this->rs['created_by']=$_SESSION["authname"];
  	$this->rs['created_on']=date('Y-m-d H:i:s');
    return parent::create();
  }
  function delete(){
  	return parent::delete();
  }
}