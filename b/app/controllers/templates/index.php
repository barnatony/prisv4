<?php
function _index($template_id=null) {
  require_login('Admin');
    $data['pagename']='Templates ';
	$view = new View();
	$data['view'] = $view;
	$data["template"]= $template = new Etemplate($template_id);
	if($template_id && !$template->exists())
		redirect('templates');
	$data["templates"]=$templates=$template->retrieve_many('hidden=0');
	
	
	$data['head'][]='<link rel="stylesheet" href="'.myUrl('js/plugins/summernote/summernote.min.css').'">';
    $data['body'][]=View::do_fetch(VIEW_PATH.'templates/index.php',$data);
    $data['foot'][]='<script src="'.myUrl('js/plugins/summernote/summernote.min.js').'"></script>';
    $data['foot'][]="<script src='".myUrl('js/pages/template-index.js')."'></script>";
    View::do_dump(VIEW_PATH.'layouts/layout_admin.php',$data);
  }
  ?>