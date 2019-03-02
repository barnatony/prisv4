<?php
function _delete($template_id=null) {
  require_login('Admin');    
  $result = array(false,"rowCount"=>0,"info"=>'',"data"=>'');
  $template_id=max(0,$template_id);
  $template = new Etemplate($template_id);
  if($template->exists()){
  		$template->delete();
  		$result[0] = true;
    	$result['info'] = 'Templates Deleted';
    }else{
    	$result['info'] = 'Failed to delete template';
  }
  echo json_encode($result);
}


