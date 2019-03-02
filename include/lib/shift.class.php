<?php
/*
 * ----------------------------------------------------------
 * Filename : generalDeduction.class.php
 * Author : Rufus Jackson
 * Database : company_deductions
 * Oper : General Deduction Actions
 *
 * ----------------------------------------------------------
 */
class Shift {
	var $shift_name;
	var $start_time;
	var $end_time;
	var $dayType;
	var $grace_inTime;
	var $grace_outTime;
	var $early_start;
	var $late_end;
	var $min_hrs_ot;
	var $min_hrs_half_day;
	var $min_hrs_full_day;
	var $conn; // connection var
	
	function getGeneralShift() {
		$json = array ();
		$stmt = mysqli_query ( $this->conn, "SELECT weeks,monday,tuesday,wednesday,thursday,
				                             friday,saturday,sunday FROM weekend
				                             WHERE  shift_id='SH00001'");
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		return $json;
	}
	
	function insert($mapShift) {
		
		if($this->end_time<='06:00')
			$stmt = mysqli_query ( $this->conn, "SELECT TIMEDIFF('0001-01-02 $this->end_time','0001-01-01 $this->start_time') shift_hrs;");
		else 
			$stmt = mysqli_query ( $this->conn, "SELECT TIMEDIFF('0001-01-01 $this->end_time','0001-01-01 $this->start_time') shift_hrs;");
		$row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC );
		
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO company_shifts 
				(shift_id,shift_name,start_time,end_time,is_day,grace_inTime,grace_outTime,early_start,late_end,min_hrs_ot,min_hrs_half_day,min_hrs_full_day,shift_hrs,enabled,updated_by)
				VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,1,?);" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssssssssss', $this->shift_id, $this->shift_name, $this->start_time, $this->end_time, $this->dayType, $this->grace_inTime, $this->grace_outTime, $this->early_start, $this->late_end, $this->min_hrs_ot, $this->min_hrs_half_day, $this->min_hrs_full_day,$row['shift_hrs'],$this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		for($i=1;$i<=count($_REQUEST['mapShift']);$i++){
			$stmt="";
			$stmt.="INSERT INTO weekend  SET shift_id='".$this->shift_id."',";
			foreach($_REQUEST['mapShift'][$i] as $key => $val){
				$stmt.=$key."='".$val."',";
			}
			$stmt.="weeks=$i;";
            mysqli_query($this->conn, $stmt);
		}
		return self::select(0);
	}
	/* Enable/Disable Branch */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_shifts SET enabled = ?,updated_by = ? WHERE shift_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->shift_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return self::select(0);
	}
	function update($mapShift) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE company_shifts
                            SET shift_name = ?, start_time = ?, end_time = ?, is_day = ?, grace_inTime = ?, grace_outTime = ?, early_start = ?, late_end = ?, min_hrs_ot = ? ,min_hrs_half_day = ?, min_hrs_full_day = ?,updated_by=? WHERE shift_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'sssssssssssss', $this->shift_name, $this->start_time, $this->end_time, $this->dayType, $this->grace_inTime, $this->grace_outTime, $this->early_start, $this->late_end, $this->min_hrs_ot, $this->min_hrs_half_day, $this->min_hrs_full_day,$this->updated_by , $this->shift_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		for($i=1;$i<=count($_REQUEST['mapShift']);$i++){
			$stmt="";
			$stmt.="UPDATE weekend  SET ";
			foreach($_REQUEST['mapShift'][$i] as $key => $val){
				$stmt.=$key."='".$val."',";
			}
			$stmt.="weeks=$i  WHERE shift_id='".$this->shift_id."' AND weeks=$i";
			mysqli_query($this->conn, $stmt);
		}
		return self::select(0);
	}
	
	function select($shift_id) {
		$condtion=($shift_id!='0')?"WHERE shift_id='$shift_id' AND enabled=1":'WHERE enabled=1';
		$json = array ();
		$stmt = mysqli_query ( $this->conn, "SELECT shift_id, shift_name, is_day, start_time, end_time,  grace_inTime, grace_outTime, early_start
                                             , late_end, min_hrs_ot, min_hrs_half_day, min_hrs_full_day, enabled FROM company_shifts $condtion");
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		return $json;
	}
	
	function getShiftMapped($shift_id) {
		$json = array ();
		$stmt = mysqli_query ( $this->conn, "SELECT ms.monday,ms.tuesday,ms.wednesday,ms.thursday,ms.friday,ms.saturday,ms.sunday FROM weekend ms WHERE ms.shift_id = '$shift_id' ORDER BY weeks;");
		while ( $row = mysqli_fetch_array ( $stmt, MYSQLI_ASSOC ) ) {
			array_push ( $json, $row );
		}
		$json=array (
				'select' => self::select($shift_id),
				'weeksShift' => $json
		);
		return $json;
		
	}
}
?>