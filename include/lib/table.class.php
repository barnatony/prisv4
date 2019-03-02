<?php
class Table{
	var $header = array();
	var $data = array();
	var $footer =array();
	protected $total=array();
	
	function __construct($header=array(),$data=array(),$footer=null){
		$this->header = $header;
		$this->data=$data;
		$this->footer=$footer;
	}
	function tableSum($data=array()){
		$data=$this->data;
		$total=$this->total;
		$total=array();
		foreach($data as $key=>$row){
			//each row will come here
			foreach($row as $k=>$column){
				//each column will come here
	
				if(is_numeric($column))
					if(!isset($total[$k]))
						$total[$k] = (float) $column;
						else
							$total[$k] += (float) $column;
							else
								$total[$k] = "-";
			}
	
		}
		return $total;
		//array("-","-",155,155,0)
	}
	function print_table($header,$data,$isRemoveIndices=false,$isTotal=false){
		//if you set remove indices as true and is total as false - table with removed zeros in it.
		//if you set removeIndices as false and total as true - table without zeros removed but totalled and printed footer
		//if removeIndices as true and isTotal as true - table with removed zeros and totalled and printed footer
	    $this->header=$header;
	    $this->data=$data;
        $this->isRemoveIndices=$isRemoveIndices;
        $this->isTotal=$isTotal;

		//case 1
		if($isRemoveIndices==true && $isTotal==false){
			$total =$this->tableSum($data);
			$this->header = $this->removeZeroIndices(array($header),$total);
			$this->data = $this->removeZeroIndices($data,$total);
			//echo $this->build_table($this->removeZeroIndices(array($header),$total),$this->removeZeroIndices($data,$total));
		}
	
		//case 2
	
		if($isRemoveIndices==false && $isTotal==true){
			$total =$this->tableSum($data);
			$this->header=array($header);
			$this->data=$data;
			$this->footer=array($total);
			//echo $this->build_table($this->removeZeroIndices(array($header),array($total)),$this->removeZeroIndices($data,array($total)),$this->removeZeroIndices(array($total),array($total)));
		}
	
		//case 3
		if($isRemoveIndices==true && $isTotal==true){
			$total =$this->tableSum($data);
			$this->header= $this->removeZeroIndices(array($header),$total);
			$this->data= $this->removeZeroIndices($data,$total);
			$this->footer=$this->removeZeroIndices(array($total),$total);
			
		    //echo $this->build_table($this->removeZeroIndices(array($header),($total)),$this->removeZeroIndices($data,($total)),$this->removeZeroIndices(array($total),($total)));
		}
		//case 4
		if($isRemoveIndices==false && $isTotal==false){
			$this->header=array($header);
			$this->data=$data;
			//echo $this->build_table(array($header),$data);
		}
	}
	//array(0=>("inr_format"))
	//styling (0=>"width:5%;")
	 function build_table($header,$data,$footer=null,$formatting=null){
	 	
		$html = '<table class = "Table1" id="activity" border=1>';
	    
		foreach(($header) as $key=>$head){
			$html .="<thead> <tr >";
			
			foreach($head as $key=>$value){
				$html .= '<th>' . $value . '</th>';
			}
			$html .="</tr> </thead>";
		}
	
	
		// data rows
		$html .="<tbody> ";
		foreach(($data) as $key=>$value){
			$html .= "<tr>";
			foreach($value as $key2=>$value2){
				$html .= '<td>' . $value2 . '</td>';
			}
			$html .= "</tr>";
		}
		$html .="</tbody>";
		
		if($footer){
			foreach(($footer) as $key=>$foot){
				$html .= '<tfoot>';
				foreach($foot as $key=>$value){
					$html .= '<th>' . $value . '</th>';
				}
				$html .= '</tfoot>';
			}
		}
		// finish table and return it
	
		$html .= '</table>';
	
		return $html;
	}
	
	 
	
	function removeZeroIndices($data = array(),$total) {
		$zero_indices =array();
		foreach($total as $k=>$field){
			if(is_numeric($field))
				if($field ==0)
					$zero_indices []=$k;
		}
		//[4,5]
		
		foreach($data as $key=>$row){
			foreach($zero_indices as $k=>$zero_index){
				//if($data[$key][$zero_index]["children"])
				unset($data[$key][$zero_index]);
			}
		}
		return $data;
	}
	
}