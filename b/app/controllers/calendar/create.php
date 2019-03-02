<?php 
function _create(){
	//require_login('Admin');
	$result = array(false,"rowCount"=>0,"info"=>'');
	
	//Validations
	/*try{
		if(!isset($_REQUEST['category']) || $_REQUEST['category']=="")
			throw new Exception("Select a valid category");
		if(!isset($_REQUEST['title']) || $_REQUEST['title']=="")
			throw new Exception("Enter the title");
		if(!isset($_REQUEST['start']) || $_REQUEST['start']=="")
			throw new Exception("Select the StartDate");
		if(!isset($_REQUEST['end']) || $_REQUEST['end']=="")
			throw new Exception("Select the EndDate");
	}catch (Exception $e) {
		$result[0]=false;
		$result["info"]=$e->getMessage();
		die(json_encode($result));
	}*/
	
	
	$event = new Event();
	$_REQUEST["start"]=date("Y-m-d H:i:s", strtotime(str_replace("/","-",$_REQUEST["start"])));
	$_REQUEST["end"]=date("Y-m-d H:i:s", strtotime(str_replace("/","-",$_REQUEST["end"])));
	
	
	
	if(isset($_REQUEST['branches']) && $_REQUEST['branches']!=""){
		$branch= explode(',', $_REQUEST['branches']);
	}else{
		$branch=$_REQUEST['branch'];
	}
	 
	 
	foreach ($branch as $k => $branch){
		$branches[$k]['category']=$_REQUEST['category'];
		//$branches[$k]['holiday_id']=$_REQUEST['holiday_id'];
		$branches[$k]['title']=$_REQUEST['title'];
		$branches[$k]['start_date']=$_REQUEST['start'];
		$branches[$k]['end_date']=$_REQUEST['end'];
		$branches[$k]['branch_id']=$branch;
		$branches[$k]['updated_by']=$_SESSION ['login_id'];
		$branches[$k]['updated_on'] =date('Y-m-d H:i:s');
		
	}
	
	
	$result= $event->insert("holidays_event",array("branch_id","category","title","start_date","end_date","updated_on","updated_by"),$branches)->result;
	
	
	echo json_encode($result);
}
?>