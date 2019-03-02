<?php
class Etemplate extends Model {

  function __construct($template_id='') {
    parent::__construct('id','email_templates'); //primary key = uid; tablename = users
    $this->rs['id'] = '';
    $this->rs['tplname'] = '';
    $this->rs['language_id'] = '';
    $this->rs['subject'] = '';
    $this->rs['message'] = '';
    $this->rs['send'] = '';
    $this->rs['core'] = '';
    $this->rs['notification_type'] = '';
    $this->rs['hidden'] = '';
    if ($template_id)
      $this->retrieve($template_id);
  }

  function create() {
//    $this->rs['created_dt']=date('Y-m-d H:i:s');
    return parent::create();
  }
  function delete(){
  	return parent::delete();
  }
}