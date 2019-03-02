<?php
function _view($n=0) {
	require_login('Admin');
	$data['pagename']='Users view ';
	$view = new View();
	$data['view'] = $view;
	$user = new User();
	$dbh = getdbh();
	$totQuery ="SELECT COUNT(user_id) total FROM users";
	$stmt=$dbh->query($totQuery);
	$rs = $stmt->fetch(PDO::FETCH_ASSOC);
	$data["total"]=$total = $rs['total'];


	//search
	$wherewhat =array();
	$bindings = array();
	$totalFilter =array();
	$searched_count = false;
	if(isset($_REQUEST["s"]) && $_REQUEST["s"] !=""){
		$wherewhat[] =" name LIKE  ?";
		array_push($bindings, "%{$_REQUEST["s"]}%");
		$searched_count = true;
		$totalFilter[]= " name LIKE '%{$_REQUEST["s"]}%' ";
	}

	if(isset($_REQUEST["e"]) && $_REQUEST["e"] !=""){
		$wherewhat[]=" email LIKE  ?";
		array_push($bindings, "%{$_REQUEST["e"]}%");
		$searched_count = true;
		$totalFilter[]= "  email LIKE '%{$_REQUEST["e"]}%' ";
	}
		
	if(isset($_REQUEST["admin"]) && $_REQUEST["admin"] !=""){
		$wherewhat[]=" privilage=?";
		array_push($bindings, "Admin");
		$searched_count = true;
		$totalFilter[]= "  privilage = 'Admin' ";
	}
	$wherewhat = implode(" AND ", $wherewhat);
	if($searched_count){
		$totQuery ="SELECT COUNT(user_id) total FROM users WHERE ".implode(" AND ", $totalFilter);
		$stmt=$dbh->query($totQuery);
		$rs = $stmt->fetch(PDO::FETCH_ASSOC);
		$data["searched_count"]=  $total=$rs['total'];
	}


	if($n!="all" || $n==""){
		$paginationConfig["per_page"]=$limit=12;
		$limit="$n,$limit";
		$data['pagination']=pagination::makePagination($n,$total,myUrl("users/view"),getRequestURI(),$paginationConfig);
	}else{
		$limit = "";
	}
	
	$data["users"]	= $users = $user->retrieve_many($wherewhat,$bindings," created_dt DESC ",$limit);
	$data['foot'][]="<script src='".myUrl('js/pages/users-view.js')."'></script>";
	$data['body'][]=View::do_fetch(VIEW_PATH.'users/view.php',$data);
	View::do_dump(VIEW_PATH.'layouts/layout_admin.php',$data);
}
?>