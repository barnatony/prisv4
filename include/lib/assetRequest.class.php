<?php
/*
 * ----------------------------------------------------------
 * Filename : assetRequest.class.php
 * Author : Rufus
 * Database : asset_request
 * Oper : assetRequest Actions
 *
 * ----------------------------------------------------------
 */
require_once (__DIR__ . "/notification.class.php");
class AssetRequest {
	var $request_id;
	var $employee_id;
	var $asset_type;
	var $from_date;
	var $to_date;
	var $purpose;
	var $description;
	var $status;
	var $asset_id;
	var $issue_on;
	var $issue_notes;
	var $updated_by;
	var $return_on;
	var $return_notes;
	var $conn;
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	function selectAsset($type) {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT asset_id,asset_status, asset_name, asset_type FROM assets WHERE enabled='1' AND asset_status='available' AND asset_type='$type' " );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		
		return $a_json;
	}
	function selectAssetForUpdate($type) {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT asset_id,asset_status, asset_name, asset_type FROM assets WHERE enabled='1' AND asset_status='available'  " );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	public function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO asset_requests
                                              (request_id, employee_id, asset_type, from_date, to_date, purpose, description,
				                              status, asset_id, issue_on, issue_notes,updated_by)
				                              VALUES (?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssssssss', $this->request_id, $this->employee_id, $this->asset_type, $this->from_date, $this->to_date, $this->purpose, $this->description, $this->status, $this->asset_id, $this->issue_on, $this->issue_notes, $this->updated_by );
		
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		$notification = new Notification();
		$notification ->connection = $this->conn;
		$insertNotif = $notification->insertNotifications('AssetRequested', $this->employee_id,'Admin',$this->request_id,'has requested a asset('.$this->asset_type.') for <b>'.$this->purpose.'</b>');
		
		
		return $result;
	}
	public function update($requested_id) {
		$stmt = mysqli_prepare ( $this->conn, "Update asset_requests SET status = ?,asset_id=?,issue_on = STR_TO_DATE(?,'%d/%m/%Y'), issue_notes = ?,return_on=STR_TO_DATE(?,'%d/%m/%Y'),return_notes=?,updated_by = ? where request_id= ?" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssss', $this->status, $this->asset_id, $this->issue_on, $this->issue_notes, $this->return_on, $this->return_notes, $this->updated_by, $requested_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function selectAssetRequest() {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT request_id,CONCAT(er.employee_name,' ',er.employee_lastname) employee_name, c.asset_type, CONCAT(DATE_FORMAT( c.from_date,'%d/%m/%y'),' - ',DATE_FORMAT( c.to_date,'%d/%m/%Y')) AS date,
												c.purpose, c.status,c.employee_id
												FROM asset_requests c INNER JOIN
												employee_work_details er ON c.employee_id = er.employee_id
												ORDER BY c.updated_on DESC" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function selectAssetRequestByEmp($employee_id) {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT CONCAT(er.employee_name,' ',er.employee_lastname) employee_name, c.asset_type, CONCAT(DATE_FORMAT( c.from_date,'%d/%m/%y'),' - ',DATE_FORMAT( c.to_date,'%d/%m/%Y')) AS date,
												c.purpose, c.status,c.request_id,c.employee_id
												FROM asset_requests c INNER JOIN
												employee_work_details er ON c.employee_id = er.employee_id
												 WHERE c.employee_id='$employee_id'" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	public function insertByEmp() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO asset_requests
                                              (request_id, employee_id, asset_type, from_date, to_date, purpose, description,
				                              updated_by)
				                              VALUES (?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),STR_TO_DATE(?,'%d/%m/%Y'),?,?,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssss', $this->request_id, $this->employee_id, $this->asset_type, $this->from_date, $this->to_date, $this->purpose, $this->description, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		$notification = new Notification();
		$notification ->connection = $this->conn;
		$insertNotif = $notification->insertNotifications('AssetRequested', $this->employee_id,'Admin',$this->request_id,'has requested a asset('.$this->asset_type.') for <b>'.$this->purpose.'</b>');
		
		return $result;
	}
	function selectAssetRequestById($request_id) {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT er.employee_id,CONCAT(er.employee_name,' ',er.employee_lastname) employee_name, c.asset_type,CONCAT(DATE_FORMAT( c.from_date,'%d/%m/%y'),'-',DATE_FORMAT( c.to_date,'%d/%m/%Y')) AS date,
				c.purpose,c.description, c.status,c.asset_id as asset_ids,DATE_FORMAT(c.issue_on,'%d/%m/%y') as issue_on,c.issue_notes,c.request_id,c.asset_type,DATE_FORMAT(c.return_on,'%d/%m/%y')as return_on,c.return_notes,ast.asset_id,ast.asset_name
				FROM asset_requests c INNER JOIN
				employee_work_details er ON c.employee_id = er.employee_id
        LEFT JOIN assets ast ON c.asset_id = ast.asset_id 
				WHERE c.request_id='$request_id'" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function downloadGeneratePdf($request_id, $employee_id) {
		$a_json = array ();
		include ("../../js/mpdf/mpdf.php");
		$mpdf = new mPDF ( 'en-GB-x', 'L', '', '', 10, 10, 10, 10, 6, 3 );
		$htmlStyle = '<style>
	   .panel-body{display: block;padding: 15px;background: #fff;color: #333;border:1px solid;margin-top:10px;}
	 	#table_id td{padding:15px}	
	   .panel-body h4{text-align:center;background:#0288D1;color:#fff;}
		body {font-family: Open Sans, sans-serif; font-size: 10pt;}
		</style>';
		
		$stmt = "SELECT wd.employee_name,wd.employee_id,wd.employee_id,CONCAT(wd.employee_name,' ',wd.employee_lastname) Name,cd.department_name,cs.designation_name,cb.branch_name
				 FROM employee_work_details wd
				 INNER JOIN company_departments cd
				 ON wd.department_id = cd.department_id
				 INNER JOIN company_designations cs
				 ON wd.designation_id = cs.designation_id
				 INNER JOIN company_branch cb
				 ON wd.branch_id = cb.branch_id
				 WHERE wd.employee_id = '$employee_id'";
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$name = $row ['employee_name'];
			$html .= '<br/><div class="panel-body"><h4>Employee Details</h4>';
			$html .= '<table style="width:95%;" id="table_id"><tr>';
			$html .= "<td><p><span class='bold'><b>Employee Id</b></td><td>&nbsp;:</td><td>" . $row ['employee_id'] . "</p></td>";
			$html .= "<td><p><span class='bold'><b>Name</b></td><td>&nbsp;:</td><td>" . $row ['Name'] . "</p></td></tr>";
			$html .= "<tr><td><p><b>Department:</b></td><td>&nbsp;:</td><td>" . $row ['department_name'] . "</p></td>";
			$html .= "<td><p><b>Designation:</b></td><td>&nbsp;:</td><td>" . $row ['designation_name'] . "</p></td></tr>";
			$html .= "<tr><td><p><b>Branch:</b></td><td>&nbsp;:</td><td>" . $row ['branch_name'] . "</p></tr></table></div>";
		}
		$stmt = "SELECT a.asset_id,a.asset_name,a.asset_type,ar.from_date,ar.to_date,ar.issue_on,ar.issue_notes,CONCAT('Administrator','- ',ar.updated_by) issued_by
			FROM assets a
			INNER JOIN asset_requests ar
			ON a.asset_id = ar.asset_id
			WHERE ar.request_id = '$request_id'";
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$html .= '<br/><div class="panel-body"><h4>Asset Details</h4>';
			$html .= '<table style="width:95%" id="table_id"><tr>';
			$html .= "<td><p><b>Asset Id</b></td><td>&nbsp;:</td><td>" . $row ['asset_id'] . "</p></td>";
			$html .= "<td><p><b>Name</b></td><td>&nbsp;:</td><td>" . $row ['asset_name'] . "</p></td></tr>";
			$html .= "<tr><td><p><b>Type</b></td><td>&nbsp;:</td><td>" . $row ['asset_type'] . "</p></td>";
			$html .= "<td><p><b>From Date</b></td><td>&nbsp;:</td><td>" . $row ['from_date'] . "</p></td></tr>";
			$html .= "<tr><td><p><b>To Date</b></td><td>&nbsp;:</td><td>" . $row ['to_date'] . "</p></td>";
			$html .= "<td><p><b>Issued On</b></td><td>&nbsp;:</td><td>" . $row ['issue_on'] . "</p></td></tr>";
			$html .= "<tr><td><p><b>Issued Notes</b></td><td>&nbsp;:</td><td>" . $row ['issue_notes'] . "</p></td>";
			$html .= "<td><p><b>Issued By</b></td><td>&nbsp;:</td><td>" . $row ['issued_by'] . "</p></td></table></tr></div>";
		}
		$html .= "<br/><br/><br/><br/><p style='margin-top:10px;'><b>Note:</b>This is Computer Generated Slip And Does Not Require Any Signature</p>";
		$html .= $htmlStyle;
		$headers = '<h3 style="font-size:12pt;font-weight:600;text-align:center">Issue Bill</h3>';
		$footer = '<table style="width:100%;"> <tr style="background-color: #1957A2" ><td colspan="4" style="text-align:center;color:#fff">@poweredby <a style="color:#fff" href="http://basspris.com">Basspris</a>-Online Payroll System</td></tr></table>';
		$mpdf->setHTMLHeader ( $headers, '', TRUE );
		$mpdf->setHTMLFooter ();
		$mpdf->WriteHTML ( $html );
		$mpdf->Output ( $name . '.pdf', D );
		exit ();
	}
}