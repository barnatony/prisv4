<?php
/*
 * ----------------------------------------------------------
 * Filename : Claim.class.php
 * Author : Rajasundari
 * Database : Claim
 * Oper : Claim Actions
 *
 * ----------------------------------------------------------
 */
require_once (__DIR__ . "/notification.class.php");

class ClaimRule {
	/* Member variables */
	var $claim_id;
	var $amount_approve;
	var $admin_remarks;
	var $claim_name;
	var $alias_name;
	var $category_type;
	var $sub_type;
	var $class;
	var $amount_from;
	var $amount_to;
	var $updated_by;
	var $claimId_mapp;
	var $applicable_id;
	var $applicable_for;
	var $status;
	var $employee_id;
	var $conn;
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	/* Insert New claimRule */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO claim_rules
                               (claim_rule_id, claim_name, alias_name, category_type, sub_type, class, amount_from, amount_to, enabled, updated_by)  
							   VALUES (?,?,?,?,?,?,?,?,1,?)" )or die(mysqli_error($this->conn));
		mysqli_stmt_bind_param ( $stmt, 'sssssssss', $this->claim_id, $this->claim_name, $this->alias_name, $this->category_type, $this->sub_type, $this->class, $this->amount_from, $this->amount_to, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		return $this->select ();
	}
	
	/* Update claimRule Using claimRule ID */
	function update() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE claim_rules SET 
				claim_name=?, alias_name=?, category_type=?, sub_type=?, class=?, amount_from=?, amount_to=?,updated_by=? WHERE claim_rule_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'sssssssss', $this->claim_name, $this->alias_name, $this->category_type, $this->sub_type, $this->class, $this->amount_from, $this->amount_to, $this->updated_by, $this->claim_id ) or die(mysqli_error($this->conn));
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $this->select ();
	}
	function select() {
		$a_json = array ();
		$stmt = "SELECT CONCAT(cr.claim_rule_id,'_',cr.claim_name)  cliamRules FROM claim_rules cr WHERE  cr.enabled=1;";
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	
	/* Claims Select For based on theri employees */
	function selectBasedEmplouee($employee_id) {
		$a_json = array ();
		$stmt = "SELECT cr.category_type,CONCAT(cr.category_type,'_',cr.sub_type,'_',IF (cr.class = '', '-',cr.class),'_',cr.amount_from,'_',cr.amount_to) ruleData,cr.claim_name,cm.claim_rule_id
														FROM claim_mapping cm
														INNER JOIN employee_work_details w
														ON w.employee_id = cm.applicable_id
														OR  cm.applicable_id =w.designation_id
														INNER JOIN claim_rules cr
														ON cr.claim_rule_id = cm.claim_rule_id
														WHERE w.employee_id='$employee_id';";
		$result = mysqli_query ( $this->conn, $stmt );
		
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	
	/* Enable/Disable LeaveRule */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE claim_rules SET enabled =?,updated_by = ?  WHERE claim_rule_id = ?" )or die(mysqli_error($this->conn));
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->claim_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	/* Delete claimRule */
	function delete() {
		$stmt = mysqli_prepare ( $this->conn, "DELETE FROM claim_mapping WHERE claim_rule_id = ? AND applicable_id=?" )or die(mysqli_error($this->conn));
		mysqli_stmt_bind_param ( $stmt, 'ss', $this->claim_id, $this->applicable_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function MappedWith() {
		$queryStmt = "INSERT INTO claim_mapping  (claim_rule_id, applicable_for, applicable_id,updated_by) 
				VALUES ";
		foreach ( $this->applicable_id as $mappedIds ) {
			$queryStmt .= "('" . $this->claimId_mapp . "','" . $this->applicable_for . "','" . $mappedIds . "','" . $this->updated_by . "'),";
		}
		$result = mysqli_query ( $this->conn, trim ( $queryStmt, "," ) ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function selectMappedClaim() {
		$a_json = array ();
		$stmt = "SELECT c.claim_rule_id,cr.claim_name, (CASE WHEN c.applicable_for = 'E' THEN 'Employee'
            WHEN c.applicable_for = 'F' THEN 'Designation' END) applicable_for,(CASE WHEN c.applicable_for = 'E' THEN CONCAT(w.employee_name,' ',w.employee_lastname)
            WHEN c.applicable_for = 'F' THEN d.designation_name END) employee_name,CONCAT(c.claim_rule_id,'_',applicable_id) 'Actions' FROM claim_mapping c INNER JOIN claim_rules cr ON c.claim_rule_id = cr.claim_rule_id
            LEFT JOIN employee_work_details w ON c.applicable_id = (CASE WHEN c.applicable_for = 'E' THEN w.employee_id
            WHEN c.applicable_for = 'F' THEN '' END) LEFT JOIN company_designations d ON  c.applicable_id = (CASE WHEN c.applicable_for = 'F' THEN d.designation_id
            WHEN c.applicable_for = 'E' THEN  '' END) ";
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	public function getAllClaim() {
		$a_json = array ();
		$stmt = "SELECT c.claim_id,c.claim_rule_id,c.employee_id, cr.employee_name ,c.purpose,c.description,CONCAT(DATE_FORMAT(c.from_date,'%d/%m/%y'),' - ',DATE_FORMAT(c.to_date,'%d/%m/%Y')) AS date,c.amount,c.reference_no,c.status,c.amount_approved,c.approved_on,c.bill_url from claims c INNER JOIN employee_work_details cr ON c.employee_id = cr.employee_id WHERE c.status !='D' ORDER BY c.status;";
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	public function getProcessedClaim() {
		$a_json = array ();
		$query= "SELECT DATE_FORMAT(processed_on,'%d/%m/%Y %h:%i %p') processed_on,SUM(c.amount) amount,COUNT(c.processed_on) count,c.employee_id,c.status FROM claims c WHERE c.status='R' GROUP BY c.processed_on DESC;";
		$result = mysqli_query ( $this->conn, $query );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	public function getProcessedClaims($processed_on){
		$a_json=array();
		$query="SELECT CONCAT(w.employee_name,' [',c.employee_id,']') employee_name,c.amount,c.purpose
                FROM claims c
                INNER JOIN employee_work_details w
                ON c.employee_id = w.employee_id
                WHERE  DATE_FORMAT(c.processed_on,'%d/%m/%Y %h:%i %p') = '$processed_on';";

		$result=mysqli_query($this->conn, $query) or die(mysqli_error($this->conn));
		while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
			array_push($a_json,$row);
		}
		return $a_json;
		
	}
	public function viewClamisbyInd($claimid) {
		$json = array ();
		$row = array ();
		$query = "SELECT c.claim_id,c.claim_rule_id,er.employee_name,c.employee_id,c.purpose,c.description,CONCAT(DATE_FORMAT(c.from_date,'%d/%m/%y'),' - ',DATE_FORMAT(c.to_date,'%d/%m/%Y')) AS date,c.amount,c.reference_no,c.status,c.amount_approved,c.approved_on,c.bill_url,cr.category_type,cr.sub_type,cr.class,cr.amount_from,cr.amount_to,cr.claim_name
  					FROM claims c INNER JOIN claim_rules cr ON c.claim_rule_id = cr.claim_rule_id INNER JOIN employee_work_details er ON c.employee_id = er.employee_id  WHERE c.claim_id = ?;";
		$stmt = mysqli_prepare ( $this->conn, $query )or die(mysqli_error($this->conn));
		mysqli_stmt_bind_param ( $stmt, 's', $claimid );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_errno ( $this->conn );
		mysqli_stmt_bind_result ( $stmt, $claim_id, $claim_rule_id, $employee_name, $employee_id, $purpose, $description, $date, $amount, $reference, $status, $amount_approve, $approved_on, $bill_url, $type, $sub_type, $class, $amount_frm, $amount_to, $claim_name );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			$row ['claim_id'] = $claim_id;
			$row ['claim_rule_id'] = $claim_rule_id;
			$row ['employee_name'] = $employee_name;
			$row ['employee_id'] = $employee_id;
			$row ['purpose'] = $purpose;
			$row ['description'] = $description;
			$row ['date'] = $date;
			$row ['amount'] = $amount;
			$row ['reference_no'] = $reference;
			$row ['status'] = $status;
			$row ['amount_approved'] = $amount_approve;
			$row ['approved_on'] = $approved_on;
			$row ['bill_url'] = $bill_url;
			$row ['category_type'] = $type;
			$row ['sub_type'] = $sub_type;
			$row ['class'] = $class;
			$row ['amount_from'] = $amount_frm;
			$row ['amount_to'] = $amount_to;
			$row ['claim_name'] = $claim_name;
			array_push ( $json, $row );
		}
		return $json;
	}
	public function updateClaimbyemployee($claimid) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE claims SET amount_approved =?,admin_remarks = ?,updated_by = ?,approved_on = NOW(),status = ?  WHERE claim_id = ?" )or die(mysqli_error($this->conn));
		mysqli_stmt_bind_param ( $stmt, 'sssss', $this->amount_approve, $this->admin_remarks, $this->updated_by, $this->status, $claimid );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	public function showProcessClaimByEmployee() {
		$a_json = array ();
		foreach ( $this->employee_id as $employeeId ) {
			$result = mysqli_query ( $this->conn, "SELECT c.employee_id,c.purpose, c.amount, CONCAT(er.employee_name,' ',er.employee_lastname) employee_name,CONCAT(DATE_FORMAT(c.from_date,'%d/%m/%y'),' - ',DATE_FORMAT(c.to_date,'%d/%m/%Y')) AS date,c.claim_id,c.status FROM claims c INNER JOIN employee_work_details er ON c.employee_id = er.employee_id where c.status = 'A' and c.employee_id ='$employeeId' ;" );
			while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
				array_push ( $a_json, $row );
			}
		}
		return $a_json;
	}
	public function showProcessClaimByDate($fromdate, $to_date) {
		$a_json = array ();
		$stmt = "SELECT c.employee_id,c.purpose, c.amount, CONCAT(er.employee_name,' ',er.employee_lastname) employee_name,CONCAT(DATE_FORMAT(c.from_date,'%d/%m/%y'),' - ',DATE_FORMAT(c.to_date,'%d/%m/%Y')) AS date,c.claim_id,c.status  FROM claims c INNER JOIN employee_work_details er ON c.employee_id = er.employee_id where c.status = 'A' and c.approved_on BETWEEN STR_TO_DATE('$fromdate','%Y/%m/%d')  and STR_TO_DATE('$to_date','%Y/%m/%d');";
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	public function processClaim($claimId) {
		$claimId = str_replace ( ",", "\",\"", $claimId );
		$claimId = "\"" . $claimId . "\"";
		$stmt = mysqli_prepare ( $this->conn, "UPDATE claims SET status = 'R',processed_on = NOW()  WHERE claim_id IN ({$claimId})" )or die(mysqli_error($this->conn));
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	public function employeeInClaims() {
		$a_json = array ();
		$stmt = "SELECT c.employee_id,CONCAT(er.employee_name,' ',er.employee_lastname) employee_name FROM claims c INNER JOIN employee_work_details er ON c.employee_id = er.employee_id	where c.status='A' GROUP BY er.employee_name;";
		$result = mysqli_query ( $this->conn, $stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
		
	}
	public function generateClaimPdf($claimId, $pdf_name) {
		$a_json = array ();
		$compdetails = array ();
		$stmt ="SELECT c.company_email,c.company_name,c.company_logo,c.company_build_name,c.company_street,c.company_area,c.company_city,
                c.company_pin_code FROM company_details c WHERE c.company_id = '" . $_SESSION ['company_id'] . "' AND c.info_flag='A';";
		$result = mysqli_query ( $this->conn,$stmt );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $compdetails, $row );
		}
		$htmlStyle = '<style>
#borderSet {
   border-collapse: collapse;
		border: 1px solid #CCC;
			text-align:center;
	}
		
th{	
color:#fff;
}	
.center_div{
	margin: 0 auto;
			
			}
			
body { font-family: Open Sans, sans-serif; font-size: 10pt; }
		</style>';
		// style Purpose
		include ("../../js/mpdf/mpdf.php");
		$claimId = str_replace ( ",", "\",\"", $claimId );
	    $claimId = "\"" . $claimId . "\"";
	    $stmt = "SELECT c.id,c.employee_id,CONCAT(wd.employee_name,' ',wd.employee_lastname) name,c.purpose,c.amount,DATE_FORMAT(c.processed_on,'%d-%m-%Y %h:%i %p') processed_on,
       		pd.employee_acc_no,pd.employee_bank_ifsc,pd.employee_bank_name,wd.payment_mode_id,pm.bank_name ,pm.bank_branch,pm.account_type
			FROM claims c 
			INNER JOIN employee_work_details wd 
			ON c.employee_id = wd.employee_id 
			INNER JOIN employee_personal_details pd 
			ON wd.employee_id = pd.employee_id
			INNER JOIN company_payment_modes pm
			ON wd.payment_mode_id = pm.payment_mode_id
			WHERE DATE_FORMAT(c.processed_on,'%d/%m/%Y %h:%i %p') IN ({$claimId}) OR c.claim_id IN ({$claimId})
			ORDER BY wd.payment_mode_id, wd.employee_name ;";
	   
	    $result = mysqli_query ( $this->conn, $stmt );
		$count = 0;
		$payment_mode_id = "";
		$mpdf = new mPDF ( 'en-GB-x', 'L', '', '', 10, 10, 10, 10, 6, 3 );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			if ($payment_mode_id == "") {
				$html.='Mode Of Payment: '.$row['account_type'];
				$html .= '<br><br><table class="center_div" style="border-collapse: collapse;width:100%"><tr style="padding: 8px;text-align:left;background-color: #39b2a9;"><th>SL No</th><th>EMP ID</th><th>Name</th><th>Claim Purpose</th><th>Amount</th><th>Account No</th><th>Bank Name</th><th>IFSC Code</th></tr>';
				
			} elseif ($payment_mode_id != $row ['payment_mode_id'] && $payment_mode_id != "") {
				$count = 1;
				$html .= '</table>';
				$html .= $mpdf->AddPage ();
				$html .= '<pagebreak />';
				$html .= '<br><table  style="border-collapse: collapse;width:100%">
						<tr><td>Mode Of Payment:' . $row ['account_type'] . ' <br> Bank Name : ' . $row ['comBankName'] . '<br>
						Account No:  ' . $row['compAccNO']. '<br> Branch Name: ' .$row['compBranch'].' <br>IFSC Code: '.$row['comIfsc'].'<br></td></tr>
						</table>';
				$html .= '<br><br><table class="center_div" style="border-collapse: collapse;width:100%"><tr style="padding: 10px;text-align:left;background-color: #39b2a9;"><th>SL No</th><th>EMP ID</th><th>Name</th><th>Amount</th><th>Account No</th><th>Bank Name</th><th>IFSC Code</th></tr>';
				$html .= '<tr><td id="borderSet" style="padding: 10px">' . $count . '</td><td id="borderSet" style="padding: 10px">' . $row ['employee_id'] . '</td><td id="borderSet" style="padding: 10px">' . $row ['name'] . '</td><td id="borderSet" style="padding: 10px">' . $row ['amount'] . '</td><td id="borderSet" style="padding: 10px">' . $row ['employee_acc_no'] . '</td><td id="borderSet" style="padding: 10px">'.str_replace("_", " ", $row['employee_bank_name']).'</td><td id="borderSet" style="padding: 10px">' . $row ['employee_bank_ifsc'] . '</td></tr>';
			}
			$count ++;
			$payment_mode_id = $row ['payment_mode_id'];
			$html .= '<tr><td id="borderSet" style="padding: 10px">' . $count . '</td><td id="borderSet" style="padding: 10px">' . $row ['employee_id'] . '</td><td id="borderSet" style="padding: 10px">' . $row ['name'] . '</td><td id="borderSet" style="padding: 10px">' . $row ['purpose'] . '</td><td id="borderSet" style="padding: 10px">' . $row ['amount'] . '</td><td id="borderSet" style="padding: 10px">' . $row ['employee_acc_no'] . '</td><td id="borderSet" style="padding: 10px">'.str_replace("_", " ", $row['employee_bank_name']).' </td><td id="borderSet" style="padding: 10px">' . $row ['employee_bank_ifsc'] . '</td></tr>';
		
			$processed_on ="<br>Processed On: ".$row["processed_on"];
		}
		$html .= '</table>';
		$html .=$processed_on;
	    $html .= $htmlStyle;
	    
		$header .= '<table> <tr>
  		<th style="width:20% "><img src=../' . $compdetails [0] ['company_logo'] . ' style="width:15%"></th>
  		<td style="font-weight: bold;font-size:15px;text-align:left; ">' . ucwords ( $compdetails [0] ['company_name'] ) . '<br>' . ucwords ( $compdetails [0] ['company_build_name'] ) . ',' . ucwords ( $compdetails [0] ['company_street'] ) . ',' . ucwords ( $compdetails [0] ['company_area'] ) . ',<br>' . ucwords ( $compdetails [0] ['company_city'] ) . ',' . $compdetails [0] ['company_pin_code'] . '</td></tr></table>';
		$mpdf->setAutoTopMargin='stretch';
		$mpdf->SetHeader($header);
		$mpdf->WriteHTML ( $html );
		$mpdf->Output ( $pdf_name . '.pdf', D );
		exit ();
	}
}
/* Claim Employee */
class Claim extends ClaimRule {
	var $date_from;
	var $claim_rule_id;
	var $claim_name;
	var $description;
	var $date_to;
	var $amount;
	var $reference_no;
	var $bill_attachment;
	var $conn;
	var $updated_by;
	var $claim_id;
	Public function employeeClaiminsert($attachment) {
		$rand = mt_rand ( 10000, 9999999 );
		$this->claim_id = "CL" . $rand;
		
		if (! empty ( $attachment ["name"] )) {
			$employee_dir = file_exists ( "../../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $_SESSION ['employee_id'] );
			$emp_dir = "../../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $_SESSION ['employee_id'];
			if (! ($employee_dir)) {
				mkdir ( $emp_dir );
			}
			$target_dir = file_exists ( "../../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $_SESSION ['employee_id'] . "/Claims/" );
			$dir = "../../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $_SESSION ['employee_id'] . "/Claims/";
			if (! ($target_dir)) {
				mkdir ( $dir );
			}
			$temp1 = str_replace ( '/', "", $this->date_from ) . md5 ( '_' ) . rand ( 1, 100 );
			move_uploaded_file ( $attachment ["tmp_name"], "../../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $_SESSION ['employee_id'] . "/Claims/" . $temp1 . ".jpg" );
			$newfilename_ = "../compDat/" . $_SESSION ['company_id'] . "/empDat/" . $_SESSION ['employee_id'] . "/Claims/" . $temp1 . ".jpg";
		} else {
			$newfilename_ = "";
		}
		$bill_attachment = $newfilename_;
		
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO claims
                               (claim_id,employee_id,claim_rule_id, purpose, description, from_date, to_date, amount, reference_no, bill_url, updated_by)
							   VALUES (?,?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,?)" );
		mysqli_stmt_bind_param ( $stmt, 'sssssssssss', $this->claim_id, $this->updated_by, $this->claim_rule_id, $this->claim_name, $this->description, $this->date_from, $this->date_to, $this->amount, $this->reference_no, $bill_attachment, $this->updated_by )or die(mysqli_error($this->conn));
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		$notification = new Notification();
		$notification ->connection = $this->conn;
		$insertNotif = $notification->insertNotifications('claimRequested', $this->updated_by,'Admin',$this->claim_id,'has requested a claim for <b>'.$this->description.'</b>');
		
		return $result;
	}
	public function viewClaimbyemployee($user) {
		$json = array ();
		$row = array ();
		$query = "SELECT claim_id,claim_rule_id, purpose, description, CONCAT(DATE_FORMAT(from_date,'%d/%m/%Y'),'-',DATE_FORMAT(to_date,'%d/%m/%Y')) AS date, amount, reference_no, status, amount_approved, approved_on, bill_url,admin_remarks FROM claims where employee_id=? ORDER BY status,approved_on;";
		$stmt = mysqli_prepare ( $this->conn, $query )or die(mysqli_error($this->conn));
		mysqli_stmt_bind_param ( $stmt, 's', $user );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_errno ( $this->conn );
		mysqli_stmt_bind_result ( $stmt, $claim_id, $claim_rule_Id, $title, $description, $date, $amount, $reference_no, $status, $amount_approved, $approved_on, $bill_url, $admin_remarks );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			$row ['claim_id'] = $claim_id;
			$row ['claim_rule_id'] = $claim_rule_Id;
			$row ['purpose'] = $title;
			$row ['description'] = $description;
			$row ['date'] = $date;
			$row ['amount'] = $amount;
			$row ['reference_no'] = $reference_no;
			$row ['status'] = $status;
			$row ['amount_approved'] = $amount_approved;
			$row ['approved_on'] = $approved_on;
			$row ['bill_url'] = $bill_url;
			$row ['admin_remarks'] = $admin_remarks;
			array_push ( $json, $row );
		}
		return $json;
	}
	public function viewClamisbyId($claimid, $employee_id) {
		$json = array ();
		$row = array ();
		$query = "SELECT c.claim_id,c.claim_rule_id,c.purpose,c.description,CONCAT(DATE_FORMAT(c.from_date,'%d/%m/%y'),' - ',DATE_FORMAT(c.to_date,'%d/%m/%Y')) AS date,c.amount,c.reference_no,c.status,c.amount_approved,c.approved_on,c.bill_url,c.admin_remarks,cr.category_type,cr.sub_type,cr.class,cr.amount_from,cr.amount_to,cr.claim_name
  				FROM claims c INNER JOIN claim_rules cr ON c.claim_rule_id = cr.claim_rule_id WHERE c.claim_id = ? and c.employee_id = ?;";
		$stmt = mysqli_prepare ( $this->conn, $query )or die(mysqli_error($this->conn));
		mysqli_stmt_bind_param ( $stmt, 'ss', $claimid, $employee_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_errno ( $this->conn );
		mysqli_stmt_bind_result ( $stmt, $claim_id, $claim_rule_id, $purpose, $description, $date, $amount, $reference, $status, $amount_approve, $approved_on, $bill_url, $admin_remarks, $type, $sub_type, $class, $amount_frm, $amount_to, $claim_name );
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			$row ['claim_id'] = $claim_id;
			$row ['claim_rule_id'] = $claim_rule_id;
			$row ['purpose'] = $purpose;
			$row ['description'] = $description;
			$row ['date'] = $date;
			$row ['amount'] = $amount;
			$row ['reference_no'] = $reference;
			$row ['status'] = $status;
			$row ['amount_approved'] = $amount_approve;
			$row ['approved_on'] = $approved_on;
			$row ['bill_url'] = $bill_url;
			$row ['admin_remarks'] = $admin_remarks;
			$row ['category_type'] = $type;
			$row ['sub_type'] = $sub_type;
			$row ['class'] = $class;
			$row ['amount_from'] = $amount_frm;
			$row ['amount_to'] = $amount_to;
			$row ['claim_name'] = $claim_name;
			array_push ( $json, $row );
		}
		return $json;
	}
}
?>