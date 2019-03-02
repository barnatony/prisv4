<?php
/*
 * ----------------------------------------------------------
 * Filename : report.class.php
 * Author : Rufus Jackson
 * Database : 
 * Oper : 
 *
 * ----------------------------------------------------------
 */
require_once (__DIR__ . "/table.class.php");
class Component{
	/* Member variables */
	var $type;
	var $title;
	var $position;
	var $data=array();
	var $componentParams=array();
	function __construct($type,$title,$position,$params) {
		$this->type=$type;
		$this->title=$title;
		$this->position=$position;
		$this->merge($params)->create($type);
		return $this;
	}
	function __destruct() {
	}
	/* Member functions */
	public function create($type){
		if (method_exists($this, $type)){
			return $this->$type();
		}else{
			return FALSE;
		}
	}
	function html(){
		$this->data["data"]=$this->componentParams["data"];
		return $this;
	}
	function table(){
		$this->data['fixedColumns']=$this->componentParams['fixedColumns'];
		$this->data['status']=$this->componentParams['status'];
		$this->data["headerDepth"]=isset($this->componentParams['headerDepth'])?$this->componentParams['headerDepth']:1;
		if($this->componentParams['queryData']!=""){
			$table = new Table($this->componentParams['headers'],$this->componentParams['queryData']);
			$table->print_table($this->componentParams['headers'], $this->componentParams['queryData'],$this->componentParams['isRemoveIndices'],$this->componentParams['isTotal']);
			$this->data['tableHeaders']=$table->header[0];
			$this->data['tableData'] = $table->data;
			$this->data['tableFooters']=$table->footer[0];
		}else 
			$this->data['tableData'] = null;
		
		
		return $this;
	}
	function linechart(){
		$xLabels=$this->componentParams['xLabels']; //text which comes in xAxis
		$data=$this->componentParams['data']; // data for labels
		$lineColumn= $this->componentParams['lineColumn'];//for the column to draw diff lines
		unset($xLabels[$lineColumn]);
		$totalLines = count($data);
		$chartData = array();
		foreach($xLabels as $key=>$xLabel){
			$temp=array();
			$temp['y']=$xLabel;
			$ykeys = array();
			for($i=0;$i<$totalLines;$i++){
				$ykeys[$i]=$data[$i][0];
				$temp[$data[$i][0]]=$data[$i][$key];
			}
			array_push($chartData, $temp);
		}
		$this->data['labels']=$ykeys;
		$this->data['ykeys']=$ykeys;
		$this->data['data']= $chartData;
		//echo json_encode($chartData);
		//if($this->componentParams['data']!="")
			//$this->data['chartData'] = $this->componentParams['queryData'];
			//else
			//	$this->data['chartData'] = null;
		return $this;
	}
	function barchart(){
		$xLabels=$this->componentParams['xLabels']; //text which comes in xAxis
		$data=$this->componentParams['data']; // data for labels
		$lineColumn= $this->componentParams['lineColumn'];//for the column to draw diff lines
		unset($xLabels[$lineColumn]);
		$totalLines = count($data);
		$chartData = array();
		foreach($xLabels as $key=>$xLabel){
			$temp=array();
			$temp['y']=$xLabel;
			$ykeys = array();
			for($i=0;$i<$totalLines;$i++){
				$ykeys[$i]=$data[$i][0];
				$temp[$data[$i][0]]=$data[$i][$key];
			}
			array_push($chartData, $temp);
		}
		$this->data['labels']=$ykeys;
		$this->data['ykeys']=$ykeys;
		$this->data['data']= $chartData;
		//echo json_encode($chartData);
		//if($this->componentParams['data']!="")
		//$this->data['chartData'] = $this->componentParams['queryData'];
		//else
		//	$this->data['chartData'] = null;
		return $this;
	}
	function merge($arr) {
		if (!is_array($arr))
			return $this;
			foreach ($arr as $key => $val)
				//if (isset($this->componentParams[$key]))
					$this->componentParams[$key] = $val;
					return $this;
	}
}
?>