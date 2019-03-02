<?php
/*
 * ----------------------------------------------------------
 * Filename : assetTracker.class.php
 * Author : Rajasundari
 * Database : AssetTracker
 * Oper : AssetTracker Actions
 *
 * ----------------------------------------------------------
 */
class AssetTracker {
	/* Member variables */
	var $asset_id;
	var $asset_name;
	var $asset_type;
	var $asset_condition;
	var $from_date;
	var $to_date;
	var $warranty_date;
	var $serial_number;
	var $manufacturer;
	var $model;
	var $quantity;
	var $status;
	var $updated_by;
	var $conn;
	function __construct() {
	}
	function __destruct() {
		mysqli_close ( $this->conn );
	}
	
	/* Member functions */
	/* Insert New AssetTracker */
	function insert() {
		$stmt = mysqli_prepare ( $this->conn, "INSERT INTO assets
                                              (asset_id, asset_name, asset_type, asset_condition, purchase_date, cost, serial_number, 
				                              manufacturer, warranty_date, model, asset_status, enabled, updated_by) 
				                              VALUES (?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,?,1,?)" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssssssss', $this->asset_id, $this->asset_name, $this->asset_type, $this->asset_condition, $this->purchase_date, $this->cost, $this->serial_number, $this->manufacturer, $this->warranty_date, $this->model, $this->status, $this->updated_by );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	/* Update AssetTracker Using AssetTrackerID */
	function update() {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE assets SET 
				asset_name=?, asset_type=?, asset_condition=?, purchase_date=STR_TO_DATE(?,'%d/%m/%Y'), cost=?, serial_number=?, 
				manufacturer=?, warranty_date=STR_TO_DATE(?,'%d/%m/%Y'), model=?, asset_status=?, updated_by=? WHERE asset_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'ssssssssssss', $this->asset_name, $this->asset_type, $this->asset_condition, $this->purchase_date, $this->cost, $this->serial_number, $this->manufacturer, $this->warranty_date, $this->model, $this->status, $this->updated_by, $this->asset_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		
		/*
		 * echo "UPDATE assets SET
		 * asset_name='$this->asset_name', asset_type='$this->asset_type', asset_condition='$this->asset_condition', purchase_date=STR_TO_DATE('$this->purchase_date','%d/%m/%Y'), cost='$this->cost', serial_number='$this->serial_number',
		 * manufacturer='$this->manufacturer', warranty_date=STR_TO_DATE('$this->warranty_date','%d/%m/%Y'), model='$this->model', asset_status='$this->status', updated_by='$this->updated_by' WHERE asset_id = '$this->asset_id'";
		 */
		return $result;
	}
	function select() {
		$a_json = array ();
		$result = mysqli_query ( $this->conn, "SELECT CONCAT(cr.asset_id,'_',cr.asset_name)  cliamRules FROM asset_rules cr WHERE  cr.enabled=1" );
		while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
			array_push ( $a_json, $row );
		}
		return $a_json;
	}
	
	/* Enable/Disable AssetTracker */
	function setEnable($val) {
		$stmt = mysqli_prepare ( $this->conn, "UPDATE assets SET enabled =?,updated_by = ?  WHERE asset_id = ?" );
		mysqli_stmt_bind_param ( $stmt, 'iss', $val, $this->updated_by, $this->asset_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	
	/* Delete AssetTracker */
	function delete() {
		$stmt = mysqli_prepare ( $this->conn, "DELETE FROM asset_mapping WHERE asset_id = ? AND applicable_id=?" );
		mysqli_stmt_bind_param ( $stmt, 'ss', $this->asset_id, $this->applicable_id );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_error ( $this->conn );
		return $result;
	}
	function checkAssetId($assetId) {
		$query = "SELECT asset_id from assets where asset_id=?";
		$stmt = mysqli_prepare ( $this->conn, $query );
		mysqli_stmt_bind_param ( $stmt, 's', $assetId );
		$result = mysqli_stmt_execute ( $stmt ) ? TRUE : mysqli_errno ( $this->conn );
		mysqli_stmt_bind_result ( $stmt, $asset_id );
		mysqli_stmt_fetch ( $stmt );
		if ($assetId == $asset_id) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
}
?>