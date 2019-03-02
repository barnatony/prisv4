<?php
function _members() {
		$data['pagename']='My Team ';
		$view = new View();
		$data['view'] = $view;
		$data['head'][]='<link rel="stylesheet" href="'.myUrl('css/dashboard.css').'">';
		$data['foot'][]='<script src="'.myUrl('js/core/bootstrap.min.js').'"></script>';
		$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-core.min.js"></script>';
		$data['foot'][]='<script src="https://cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.7.5/dust-full.min.js"></script>';
		$data['foot'][]='<script src="'.myUrl('js/plugins/dust/dust-helpers.js').'"></script>';
		$data['foot'][]="<script src='https://momentjs.com/downloads/moment.js'></script>";
		$data['foot'][]='<script src="'.myUrl('js/pages/myteam-index.js').'"></script>';
		$data['body'][]=View::do_fetch(VIEW_PATH.'myteam/members.php',$data);
		View::do_dump(VIEW_PATH.'layouts/layout_frontend.php',$data);
	
}