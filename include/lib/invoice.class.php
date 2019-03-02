<?php
/*
 * ----------------------------------------------------------
 * Filename : event.handle.php
 * Classname: event.class.php
 * Author : Rajasundari
 * Database : HoildayEvent
 * Oper : HoildayEvent Actions
 *
 * ----------------------------------------------------------
 */
class Invoice {
	/* Member variables */
	var $serv_id;
	var $serv_name;
	var $serv_desc;
	var $is_variable;
	var $default_price;
	var $tax_id;
	var $title;
	var $description;
	var $tax_percentage;
	var $enabled;
	var $exempt_to;
	var $invoice_id;
	var $invoice_month;
	var $invoice_period;
	var $amount;
	var $discount;
	var $discounted_amount;
	var $taxes;
	var $net_amount;
	var $due_on;
	var $servArray;
	var $taxArray;
	var $txnid;
	var $status;
	var $txn_status;
	var $conn;
	
	/* Member functions */
	/* Insert New event */
	function serviceInsert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO services  (serv_id, serv_name, serv_desc, is_variable, default_price,exempt_to,enabled) 
				              VALUES (?,?,?,?,?,?,1)" );
		mysqli_stmt_bind_param ( $stmt, 'ssssss', $this->serv_id, $this->serv_name, $this->serv_desc, $this->is_variable, $this->default_price, $this->exempt_to );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return array (
				"result" => $result,
				"select" => self::serviceSelect () 
		);
	}
	function mapServices() {
		$a_json = array ();
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_services (company_id, serv_id, customized_price, customized_exempted,display_order) 
				              VALUES (?,?,?,?,99)" );
		mysqli_stmt_bind_param ( $stmt, 'ssss', $this->company_id, $this->serv_id, $this->default_price, $this->exempt_to );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return array (
				"result" => $result,
				"select" => self::companyServiceMap () 
		);
	}
	function serviceSelect() {
		$a_json = array ();
		$stmt = mysqli_query ( $this->conn, "SELECT serv_id,serv_name, serv_desc, is_variable,exempt_to,default_price,enabled FROM `services`" );
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function serviceUpdate() {
		$a_json = array ();
		$stmt = mysqli_prepare ( $this->conn, "UPDATE services SET 
				serv_name=?, serv_desc=?, is_variable=?, default_price=? ,exempt_to= ?  WHERE serv_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'ssssss', $this->serv_name, $this->serv_desc, $this->is_variable, $this->default_price, $this->exempt_to, $this->serv_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return array (
				"result" => $result,
				"select" => self::serviceSelect () 
		);
	}
	
	/* Enable/Disable Branch */
	function serviceEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE services SET enabled = ? WHERE serv_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'is', $val, $this->serv_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return array (
				"result" => $result,
				"select" => self::serviceSelect () 
		);
	}
	function serviceEdit() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_services SET customized_price=? ,customized_exempted= ? WHERE serv_id = ? AND company_id=?" );
		mysqli_stmt_bind_param ( $stmt, 'ssss', $this->default_price, $this->exempt_to, $this->serv_id, $this->company_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return array (
				"result" => $result,
				"select" => self::companyServiceMap () 
		);
	}
	function taxEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE taxes SET enabled = ? WHERE tax_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'is', $val, $this->tax_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return array (
				"result" => $result,
				"select" => self::taxSelect () 
		);
	}
	function taxInsert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO taxes (tax_id, title, description, tax_percentage, enabled)  
				VALUES (?,?,?,?,1)" );
		mysqli_stmt_bind_param ( $stmt, 'ssss', $this->tax_id, $this->title, $this->description, $this->tax_percentage );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return array (
				"result" => $result,
				"select" => self::taxSelect () 
		);
	}
	function taxSelect() {
		$a_json = array ();
		$stmt = mysqli_query ( $this->conn, "SELECT tax_id, title, description, tax_percentage, enabled FROM `taxes`" );
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function selectedTax($companyId) {
		$a_json = array ();
		$stmt = mysqli_query ( $this->conn, "SELECT ct.company_id,t.tax_id, t.title, t.tax_percentage
				FROM taxes t LEFT  JOIN company_tax ct ON t.tax_id = ct.tax_id
				AND company_id='$this->company_id' AND enabled=1 WHERE ct.tax_id IS NOT NULL ORDER BY ct.display_order ASC" );
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function mapTaxes() {
		$a_json = array ();
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_tax (company_id, tax_id, display_order) 
				              VALUES (?,?,99)" );
		mysqli_stmt_bind_param ( $stmt, 'ss', $this->company_id, $this->tax_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return array (
				"result" => $result,
				"select" => self::companyServiceMap () 
		);
	}
	function taxUpdate() {
		$a_json = array ();
		$stmt = mysqli_prepare ( $this->conn, "UPDATE taxes SET
				 title=?, description=?, tax_percentage=?  WHERE tax_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'ssss', $this->title, $this->description, $this->tax_percentage, $this->tax_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return array (
				"result" => $result,
				"select" => self::taxSelect () 
		);
	}
	function companyServiceMap() {
		$a_json = array (
				"selectSev" => array (),
				"selectTax" => array (),
				"taxes" => array (),
				"services" => array () 
		);
		$stmt = mysqli_query ( $this->conn, "SELECT cs.company_id,s.serv_id, s.serv_name,s.is_variable, s.exempt_to, s.default_price 
				FROM services s LEFT  JOIN company_services cs ON s.serv_id = cs.serv_id 
				AND company_id='$this->company_id' AND enabled=1 WHERE cs.serv_id IS NULL" );
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $a_json ["selectSev"], $row );
		}
		
		$stmt = mysqli_query ( $this->conn, "SELECT ct.company_id,t.tax_id, t.title, t.tax_percentage
				FROM taxes t LEFT  JOIN company_tax ct ON t.tax_id = ct.tax_id 
				AND company_id='$this->company_id' AND enabled=1 WHERE ct.tax_id IS NULL" );
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $a_json ["selectTax"], $row );
		}
		
		$stmt = mysqli_query ( $this->conn, "SELECT cs.company_id,s.serv_id, s.serv_name,s.is_variable, cs.customized_exempted exempt_to, cs.customized_price default_price
				FROM services s LEFT  JOIN company_services cs ON s.serv_id = cs.serv_id
				AND company_id='$this->company_id' AND enabled=1 WHERE cs.serv_id IS NOT NULL ORDER BY cs.display_order ASC" );
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $a_json ["services"], $row );
		}
		
		array_push ( $a_json ["taxes"], self::selectedTax ( $this->company_id ) );
		return $a_json;
	}
	function sortoutData($data) {
		$sortServices = 0;
		$sortTaxes = 0;
		$stmForService = "";
		$stmForTax = "";
		foreach ( $data as $order ) {
			if (isset ( $order ['tax_id'] )) {
				$stmForService .= "UPDATE company_tax SET display_order = $sortTaxes
        WHERE  company_id = '" . $order ['comId'] . "' AND tax_id = '" . $order ['tax_id'] . "';";
				$sortTaxes ++;
			} elseif ((isset ( $order ['serv_id'] ))) {
				$stmForTax .= "UPDATE company_services SET display_order = $sortServices
        WHERE  company_id ='" . $order ['comId'] . "' AND serv_id = '" . $order ['serv_id'] . "';";
				$sortServices ++;
			}
		}
		mysqli_multi_query ( $this->conn, $stmForService . $stmForTax );
		do {
			if ($result = mysqli_store_result ( $this->conn )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->conn ) && mysqli_next_result ( $this->conn ) );
		
		return array (
				"result" => true,
				"select" => self::companyServiceMap () 
		);
	}
	function performaInvoice() {
		$a_json = array (
				"address" => array (),
				"services" => array (),
				"taxes" => array () 
		);
		$stmt = mysqli_query ( $this->conn, "SELECT a.serv_id,a.serv_name,a.is_variable,a.empCount,a.exempt_to,a.default_price,a.address,(CASE WHEN a.is_variable=1 THEN
        (IF((a.empCount-a.exempt_to)>0,(a.empCount-a.exempt_to),0))
        ELSE NULL
        END) qty,(CASE WHEN a.is_variable=1 THEN
        (a.default_price*IF((a.empCount-a.exempt_to)>0,(a.empCount-a.exempt_to),0))
        ELSE a.default_price
        END) serv_amount FROM (SELECT cs.serv_id,s.serv_name,s.serv_desc,s.is_variable,
(SELECT COUNT(employee_id) FROM $this->company_username.employee_work_details w WHERE w.enabled) empCount,
      IFNULL(cs.customized_exempted,s.exempt_to) exempt_to, 
      IFNULL(cs.customized_price,s.default_price) default_price,
      CONCAT(cd.company_name,'_',
        cd.company_build_name,'_',company_area,'_',
        cd.company_street,'_',cd.company_state,'_',CAST(cd.company_pin_code AS CHAR(6) ),'_',cd.company_city,'_',cd.company_email,'_',cd.company_mobile) address
      FROM company_services cs
      INNER JOIN services s
      ON cs.serv_id = s.serv_id AND s.enabled=1
      LEFT JOIN company_details cd
      ON cd.company_id=cs.company_id AND cd.info_flag IN ('A','W')
        WHERE cs.company_id='$this->company_id'  ORDER BY cs.display_order ASC ) a;" );
		$i = 0;
		$flag = 1;
		$subTotal = 0;
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			if ($flag == 1) {
				array_push ( $a_json ["address"], $row ['address'] );
				$flag = 0;
			}
			$subTotal += $row ['serv_amount'];
			array_push ( $a_json ["services"], $row );
			unset ( $a_json ["services"] [$i] ['address'] );
			$i ++;
		}
		$a_json ["subTotal"] = $subTotal;
		array_push ( $a_json ["taxes"], self::selectedTax ( $this->company_id ) );
		return $a_json;
	}
	function generateInvoice() {
		$a_json = array ();
		$queryStmt = "";
		foreach ( $this->servArray as $key ) {
			$queryStmt .= "INSERT INTO invoice_particulars
				(company_id, invoice_id, particular_id, amount, value, type) 
				VALUES ('$this->company_id', '$this->invoice_id','" . explode ( '=', $key ) [0] . "', " . explode ( '=', $key ) [1] . ", '" . explode ( '=', $key ) [2] . "', '" . explode ( '=', $key ) [3] . "');";
		}
		foreach ( $this->taxArray as $key ) {
			$queryStmt .= "INSERT INTO invoice_particulars
			(company_id, invoice_id, particular_id, amount, value, type)
			VALUES ('$this->company_id', '$this->invoice_id','" . explode ( '=', $key ) [0] . "', " . explode ( '=', $key ) [1] . ", '" . explode ( '=', $key ) [2] . "', '" . explode ( '=', $key ) [3] . "');";
		}
		mysqli_multi_query ( $this->conn, $queryStmt );
		do {
			if ($result = mysqli_store_result ( $this->conn )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->conn ) && mysqli_next_result ( $this->conn ) );
		
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO invoices
	                            (company_id, invoice_id, invoice_month, invoice_period,
				 amount, discount, discount_amount, tax_amount, net_amount, due_on, status)
	            VALUES (?,?,?,?,?,?,?,?,?,?,'due')" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssssss', $this->company_id, $this->invoice_id, $this->invoice_month, $this->invoice_period, $this->amount, $this->discount, $this->discounted_amount, $this->taxes, $this->net_amount, $this->due_on );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return array (
				"result" => $result,
				"select" => self::viewInvoice ( $this->company_id, $this->invoice_id ) 
		);
	}
	function updateDiscount($company_id, $invoice_id) {
		$a_json = array ();
		$queryStmt = "";
		foreach ( $this->taxArray as $key ) {
			$queryStmt .= "UPDATE invoice_particulars SET amount='" . explode ( '=', $key ) [1] . "', value='" . explode ( '=', $key ) [2] . "', type='" . explode ( '=', $key ) [3] . "'
			WHERE company_id='$company_id' AND invoice_id='$invoice_id' AND particular_id='" . explode ( '=', $key ) [0] . "';";
		}
		mysqli_multi_query ( $this->conn, $queryStmt );
		do {
			if ($result = mysqli_store_result ( $this->conn )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->conn ) && mysqli_next_result ( $this->conn ) );
		$stmt = mysqli_prepare ( $this->conn, "UPDATE invoices SET
				discount=? ,discount_amount=? ,tax_amount=? ,net_amount=?  WHERE invoice_id = ? AND company_id=? " );
		mysqli_stmt_bind_param ( $stmt, 'ssssss', $this->discount, $this->discounted_amount, $this->taxes, $this->net_amount, $invoice_id, $company_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return array (
				"result" => $result,
				"select" => self::viewInvoice ( $company_id, $invoice_id ) 
		);
	}
	function invoiceSelect() {
		$stmt = mysqli_query ( $this->conn, "SELECT invoice_id FROM invoices  WHERE DATE_FORMAT(invoice_month,'%m%Y')=DATE_FORMAT('$this->invoice_month','%m%Y')  AND company_id='$this->company_id' LIMIT 0,1 " );
		if (mysqli_num_rows ( $stmt ) == 0) {
			$result = array (
					"result" => true,
					"select" => self::performaInvoice () 
			);
		} else {
			$row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC );
			$result = array (
					"result" => true,
					"select" => self::viewInvoice ( $this->company_id, $row ['invoice_id'] ) 
			);
		}
		return $result;
	}
	function viewInvoice($company_id, $invoiceId) {
		$a_json = array (
				"values" => array (),
				"services" => array (),
				"taxes" => array () 
		);
		$flag = 1;
		$stmt = mysqli_query ( $this->conn, "SELECT cs.customized_price,i.company_id,CONCAT(cd.company_name,'_',cd.company_build_name,'_',company_area,'_',cd
				.company_street,'_', cd.company_state,'_',CAST(cd.company_pin_code AS CHAR(6) ),'_',cd.company_city,'_',cd
				.company_email,'_', cd.company_mobile) address,ip.invoice_id,DATE_FORMAT(i.invoice_month,'%d/%m/%Y') invoice_month,i.invoice_period,i.amount subTotal,i.discount,i.discount_amount disCountAmount
                ,i.net_amount grandTotal,CONCAT(IFNULL(s.serv_name,' '),IFNULL(t.title,' ')) Title,ip.amount taxAmount,ip.value,ip.type,i.status,DATE_FORMAT(i.due_on,'%d/%m/%Y') due_on,t.tax_id
				FROM " . MASTER_DB_NAME . ".invoices i
				INNER JOIN " . MASTER_DB_NAME . ".invoice_particulars ip
				ON i.invoice_id = ip.invoice_id
				INNER JOIN " . MASTER_DB_NAME . ".company_details cd
				ON i.company_id = cd.company_id
				LEFT JOIN " . MASTER_DB_NAME . ".services s
				ON ip.particular_id = s.serv_id
				LEFT JOIN " . MASTER_DB_NAME . ".company_services cs
				ON cs.serv_id = s.serv_id AND cs.company_id ='$company_id' 
				LEFT JOIN " . MASTER_DB_NAME . ".taxes t
				ON ip.particular_id = t.tax_id
				WHERE i.company_id='$company_id' AND i.invoice_id ='$invoiceId';" );
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			if ($flag == 1) {
				array_push ( $a_json ["values"], $row ['address'], $row ['invoice_id'], $row ['subTotal'], $row ['discount'], $row ['disCountAmount'], $row ['grandTotal'], $row ['invoice_period'], $row ['invoice_month'], $row ['status'], $row ['due_on'] );
				$flag = 0;
			}
			if ($row ['type'] == 'service') {
				array_push ( $a_json ["services"], $row ['Title'], $row ['taxAmount'], $row ['value'], $row ['customized_price'] );
			}
			if ($row ['type'] == 'tax') {
				array_push ( $a_json ["taxes"], $row ['Title'], $row ['taxAmount'], $row ['value'], $row ['tax_id'] );
			}
		}
		return $a_json;
	}
	function dueDateupdate($company_id, $invoice_id) {
		$a_json = array ();
		$stmt = mysqli_prepare ( $this->conn, "UPDATE invoices SET
				due_on=?  WHERE invoice_id = ? AND company_id='$company_id' " );
		mysqli_stmt_bind_param ( $stmt, 'ss', $this->due_on, $invoice_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return array (
				"result" => $result,
				"select" => self::viewInvoice ( $company_id, $invoice_id ) 
		);
	}
	function select($companyId) {
		$a_json = array ();
		$stmt = mysqli_query ( $this->conn, "SELECT invoice_id, DATE_FORMAT(invoice_month,'%d/%m/%Y') invoice_month, invoice_period,
        net_amount,CONCAT(DATE_FORMAT(due_on,'%d/%m/%Y'),'_',DATEDIFF(due_on,NOW()) ) due_on,Status,(SELECT COUNT(invoice_id) FROM " . MASTER_DB_NAME . ".invoices WHERE  company_id='$companyId' AND status='due') pending FROM " . MASTER_DB_NAME . ".invoices where company_id='$companyId'" );
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	function sentEmail($companyId, $invoiceId, $emailId) {
		$row = self::viewInvoice ( $companyId, $invoiceId );
		$styleSheet = '<style>
  body { font-family: Open Sans, sans-serif; font-size: 10pt; }
 .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    line-height: 1.42857;
    padding: 8px;
    vertical-align: top;
}
   
.table td{
border-collapse: collapse;
padding:6px;
} 
th{
    text-align: left;
}
.textRightAlign{
text-align:right;
}
table.content td{
border-top:1px solid black;
border-collapse: collapse;
padding:6px;
} 
.bootomBorder{    		
border-top:1px solid black;
border-collapse: collapse;
}    	
.success{
color:#1AB394;
}
.danger{
color:#e46f61;
}
</style>';
		$html = '';
		$address = explode ( '_', $row ['values'] [0] );
		$html .= $styleSheet;
		$status = ($row ['values'] [8] == 'due') ? '<h2 class="danger">Unpaid</h2>' : '<h2 class="success">Paid</h2>';
		$html .= '<table style="width:100%">
    		<tr><td> <img src="../../compDat/masterlogo.png" alt="basspris-logo" class="col-lg-3" style="width: 17%;margin-top: 20px;"></td>
    		    <td class="textRightAlign"><h2>INVOICE</h2></td>
    		</tr>
    		<tr><td>&nbsp;&nbsp;&nbsp;No:40,Second Floor,</td><td class="textRightAlign"></td></tr>
    		<tr><td >&nbsp;&nbsp;&nbsp;Teachers Colony, Adyar,</td><td style="width:40%">Invoice Number	: <strong>' . $row ['values'] [1] . '</strong></td></tr>
    		<tr><td>&nbsp;&nbsp;&nbsp;Chennai.PIN:600020,</td><td class="textRightAlign"></td></tr>
    		<tr><td style="width:60%">&nbsp;&nbsp;&nbsp;+91 9841616416 </td><td style="width:40%">Invoice Period : ' . $row ['values'] [6] . '</td></tr>
    		</table>
    		<br>
    		<table style="width:100%">
    		<tr><td style="width:60%">' . $status .'(Due on '.$row ['values'] [9]. ')</td><td><h4>BILLING TO</h4></td></tr>
    		<tr><td ></td><td>' . $address [0] . '</td></tr>
    		<tr><td ></td><td>' . $address [1] . ',</td></tr>
    		<tr><td ></td><td>' . $address [2] . ',</td></tr>
    		<tr><td ></td><td>' . $address [3] . ',</td></tr>
    		<tr><td ></td><td>' . $address [4] . ',' . $address [5] . '-' . $address [6] . ',</td></tr>
    		<tr><td ></td><td>' . $address [7] . ',</td></tr>
    		<tr><td ></td><td> + 91 ' . $address [8] . '.</td></tr>
    		</table>
    		 
	        <table class="table content" style="border-bottom:0pt solid black;border-collapse: collapse;width:100%"><thead><tr>
    		<th>#</th><th>Particulars</th>
    		<th class="textRightAlign">Amount</th></tr></thead>
    		<tbody>';
		$i = 1;
		for($lc = 0; $lc < count ( $row ['services'] ); $lc += 4) {
			$values = (explode ( '|', $row ['services'] [$lc + 2] ) [1] != '') ? '( ' . explode ( '|', $row ['services'] [$lc + 2] ) [0] . ' active Employees ) ' : '';
			$html .= '<tr><td>' . $i . '</td><td>' . $row ['services'] [$lc] . $values . '</td><td class="textRightAlign">' . inr_format ( round ( $row ['services'] [$lc + 1] ) ) . '</td></tr>';
			$i ++;
		}
		
		$html .= '</tbody></table><table class="table" style="border-bottom:0pt solid black;border-collapse: collapse;width:100%"><tbody>
    		<tr><td style="width:50%"></td><td class="bootomBorder">Subtotal</td><td class="textRightAlign bootomBorder"> ' . inr_format ( round ( $row ['values'] [2] ) ). '</td></tr>
            <tr><td ></td><td class="bootomBorder">( - ) Discount @ ' . $row ['values'] [3] . ' %</td><td class="textRightAlign bootomBorder"> ' . inr_format ( round ( $row ['values'] [4] ) ). '</td></tr>';
		
		for($lc = 0; $lc < count ( $row ['taxes'] ); $lc += 4) {
			$html .= '<tr><td ></td><td class="bootomBorder"> ' . $row ['taxes'] [$lc] . ' ( ' . $row ['taxes'] [$lc + 2] . ' %) </td><td class="textRightAlign bootomBorder">' .inr_format ( round ( $row ['taxes'] [$lc + 1] ) ) . '</td></tr>';
		}
		
		$html .= '<tr class="h4"><td ></td><td class="bootomBorder">Grand Total</td><td class="textRightAlign bootomBorder">' . inr_format ( round ( $row ['values'] [5] ) ) . '</td></tr>
    		<tr><td ></td><td colspan="2" ><strong>' . ucfirst ( Session::newInstance ()->convert_number_to_words ( ROUND ( $row ['values'] [5] ) ) ) . 'only </strong></td></tr>
    		</tbody></table>';
		
		$companyFooter = '<table style="width:100%" id="t03">
                                  <tr><td colspan="1" ></td><td colspan="8" style="text-align: left">
                                   <b> Note : </b>This is confidential information and you are advised not to share it with others. This is a computer generated
    <p> invoice and does not require any signature.</p></table>';
		
		require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/js/mpdf/mpdf.php");
		$mpdf = new mPDF ( 'en-GB-x', 'L', '', '', 10, 10, 10, 10, 6, 3 );
		$mpdf->WriteHTML ( $html );
		$mpdf->setHTMLFooter ( $companyFooter );
		$content = $mpdf->Output ( '', 'S' );
		if (! filter_var ( $emailId, FILTER_VALIDATE_EMAIL ) === false) {
			$mailsentResult = self::emailpdf ( $content, $emailId );
		} else {
			echo "Invalid EmailId";
		}
		return $mailsentResult;
	}
	function emailpdf($content, $alterEmail) {
		$content = chunk_split ( base64_encode ( $content ) );
		$mailto = $alterEmail;
		$subject = "Invoice";
		$from_name = 'Team Support | PRIS';
		$from_mail = 'support@pris.com';
		$message = 'Please Find Attachment';
		$filename = 'Invoice.pdf';
		$boundary = "xyz";
		$emailHeader = "--$boundary\r\n";
		$emailHeader .= "content-Transfer-Encoding: 8bits\r\n";
		$emailHeader .= "content-Type: text/html; charset=ISO-8859-1\r\n\r\n";
		$emailHeader .= "$message\r\n";
		$emailHeader .= "--$boundary\r\n";
		$emailHeader .= "Content-Type:application/pdf; name=\"" . $filename . "\"\r\n";
		$emailHeader .= "Content-Dispostion: attachment; filename=\"" . $filename . "\"\r\n";
		$emailHeader .= "Content-Transfer-Encoding: base64\r\n\r\n";
		$emailHeader .= "$content\r\n";
		$emailHeader .= "--$boundary--\r\n";
		$emailHeader2 = "MIME-Version: 1.0\r\n";
		$emailHeader2 .= "From: " . $from_name . "\r\n";
		$emailHeader2 .= "Return-Path:$from_mail\r\n";
		$emailHeader2 .= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";
		$emailHeader2 .= "$boundary\r\n";
		if (@mail ( $mailto, $subject, $emailHeader, $emailHeader2, "-r" . $from_mail )) {
			$result = true;
		} else {
			$result = false;
		}
		return $result;
	}
	function payumoneyTransfer() {
		$_SESSION ['invoice_id'] = $this->invoice_id;
		if (empty ( $_REQUEST ['firstname'] ) || empty ( $_REQUEST ['email'] ) || empty ( $_REQUEST ['phone'] ) || empty ( $_REQUEST ['productinfo'] ) || empty ( $_REQUEST ['surl'] ) || empty ( $_REQUEST ['furl'] ) || empty ( $_REQUEST ['service_provider'] )) {
			return "error";
		} else {
			$stmt = mysqli_query ( $this->conn, "SELECT net_amount FROM " . MASTER_DB_NAME . ".invoices  WHERE  invoice_id='$this->invoice_id' AND company_id='$this->company_id' LIMIT 0,1 " );
			$row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC );
			$txnid = substr ( hash ( 'sha256', mt_rand () . microtime () ), 0, 20 );
			$MERCHANT_KEY = "wByzhnXQ";
			$SALT = "CF51LjCw1n";
			$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
			$hashVarsSeq = explode ( '|', $hashSequence );
			$hash_string = '';
			$hash_string = $MERCHANT_KEY . '|' . $txnid . '|' . $row ['net_amount'] . '|';
			foreach ( $hashVarsSeq as $hash_var ) {
				if ($hash_var != 'key' && $hash_var != 'txnid' && $hash_var != 'amount') {
					$hash_string .= isset ( $_REQUEST [$hash_var] ) ? $_REQUEST [$hash_var] : '';
					$hash_string .= '|';
				}
			}
			$hash_string .= $SALT;
			$hash = strtolower ( hash ( 'sha512', $hash_string ) );
			$action = 'https://test.payu.in/_payment';
			return array (
					"txnid" => $txnid,
					"key" => $MERCHANT_KEY,
					"hash" => $hash,
					"action" => $action,
					"amount" => $row ['net_amount'] 
			);
		}
	}
	function transactionAdd($company_id, $invoice_id, $txnid, $txn_status, $status) {
		$result = mysqli_multi_query ( $this->conn, "INSERT INTO " . MASTER_DB_NAME . ".transactions (company_id, invoice_id, txn_id,txn_status)
		VALUES ('$company_id','$invoice_id','$txnid','$txn_status');UPDATE " . MASTER_DB_NAME . ".invoices SET status='$status'  WHERE company_id = '$company_id'  AND invoice_id='$invoice_id'" );
		do {
			if ($result = mysqli_store_result ( $this->conn )) {
				mysqli_free_result ( $result );
			}
		} while ( mysqli_more_results ( $this->conn ) && mysqli_next_result ( $this->conn ) );
		unset ( $_SESSION ['invoice_id'] );
		return $result = true;
	}
}

?>