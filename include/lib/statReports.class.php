<?php
class Reports {
	/* Member variables */
	var $columName;
	var $headerName;
	var $date;
	var $dateOrder;
	var $conn;
    /* Member functions */
	function __construct(){
		ini_set ( 'memory_limit', MEMORY_LIMIT );
		ini_set('max_execution_time', 'MAX_EXECUTION_TIME');
		date_default_timezone_set(DEFAULT_TIMEZONE);
	}
	function reports_filter($columName, $queryStmt) {
		$a_json = array ();
		// echo "SELECT lm.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,lop,$columName,total_deduction,net_salary FROM $queryStmt";
		$result = mysqli_query ( $this->conn, "SELECT lm.employee_id,CONCAT(w.employee_name,' ',w.employee_lastname) employee_name,lop,$columName total_deduction,net_salary FROM $queryStmt " );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			$a_json ['tableValues'] [] = $row;
		}
		$a_json ['tableCaption'] = "Salary statement For $this->dateOrder";
		$a_json ['tableHeader'] = explode ( ',', $this->headerName );
		return $a_json;
	}
	function tableContent($tableName, $value) {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT $value from  $tableName" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	
	function ExportCSVFile($records) {
		// create a file pointer connected to the output stream
		$fh = fopen ( 'php://output', 'w' );
		$heading = false;
		if (! empty ( $records ))
			foreach ( $records as $row ) {
				if (! $heading) {
					// output the column headings
					fputcsv ( $fh, array_keys ( $row ) );
					$heading = true;
				}
				// loop over the rows, outputting them
				fputcsv ( $fh, array_values ( $row ) );
			}
		fclose ( $fh );
	}
	function ExportFile($records) {
		$heading = false;
		if (! empty ( $records ))
			foreach ( $records as $row ) {
				if (! $heading) {
					// display field/column names as a first row
					echo implode ( "\t", array_keys ( $row ) ) . "\n";
					$heading = true;
				}
				echo implode ( "\t", array_values ( $row ) ) . "\n";
			}
		exit ();
	}
	

}
?>