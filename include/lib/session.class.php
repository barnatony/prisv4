<?php
/*
 * ----------------------------------------------------------
 * Filename :
 * Classname: session.class.php
 * Author : Rufus Jackson
 * Database :
 * Oper : setting session variables for user roles
 * ----------------------------------------------------------
 */
class Session {
	private $session;
	private static $instance;
	private $database;
	var $role; // "HR","EMPLOYEE"
	var $isHr = false;
	
	/* Member functions */
	public function __construct() {
		if (! isset ( $_SESSION )) {
			session_start ();
		}
		// session_name ( "pris_session" );
		$this->session = $_SESSION;
		$this->role = $this->_get ( 'role' ) != '' ? $this->_get ( 'role' ) : '';
		if ($this->role == "HR") {
			$this->isHr = true;
			require_once (__DIR__ . "/database.class.php");
		}
		if ($this->role == "EMPLOYEE") {
			$this->isEmployee = true;
			require_once (__DIR__ . "/database.class.php");
		}
	}
	public static function newInstance() {
		if (! self::$instance instanceof self) {
			self::$instance = new self ();
		}
		return self::$instance;
	}
	function pris_session_start($role, $login_id, $name) {
		$this->_set ( 'role', $role );
		$this->_set ( 'hash_key', $role );
		SWITCH ($role) {
			case "HR" :
				$this->_set ( "login_id", $login_id );
				$name = $name != "" ? $name : $login_id;
				$this->_set ( "display_name", $name );
				break;
			case "EMPLOYEE" :
				$this->_set ( "employee_id", $login_id );
				$this->_set ( "employee_name", $name );
				break;
			case "CONSULTANT" :
				break;
			case "MASTER" :
				$this->_set ( "master_id", $login_id );
				$this->_set ( "master_name", $name );
				break;
		}
	}
	function pris_session_destroy() {
		session_destroy ();
	}
	function _set($key, $value) {
		$_SESSION [$key] = $value;
		$this->session [$key] = $value;
	}
	function _get($key) {
		if (! isset ( $this->session [$key] )) {
			return '';
		}
		
		return ($this->session [$key]);
	}
	function _drop($key) {
		unset ( $_SESSION [$key] );
		unset ( $this->session [$key] );
	}
	public function _setLeaveRules() {
		if ($this->isHr || $this->isEmployee) {
			if ($this->_get ( 'leaveRules' ) == "") {
				$this->database = new Database ();
				$stmt = "SELECT lr.leave_rule_id,lr.rule_name,lr. alias_name,lr.type FROM company_leave_rules lr WHERE lr.enabled=1 ORDER BY lr.type";
				$result = mysqli_query ( $this->database->getConnection (), $stmt ); // Procedure Gives who ever employees affected
				$rules = array (
						"M" => array (),
						"Q" => array (),
						"Y" => array (),
						"monthlyString" => "",
						"quarterlyString" => "",
						"yearlyString" => "" 
				);
				while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
					if ($row ['type'] == "M") {
						$rules ["M"] [] = $row;
						$rules ['monthlyString'] .= $row ['leave_rule_id'] . ",";
					} elseif ($row ['type'] == "Q") {
						$rules ["Q"] [] = $row;
						$rules ['quarterlyString'] .= $row ['leave_rule_id'] . ",";
					} elseif ($row ['type'] == "Y") {
						$rules ["Y"] [] = $row;
						$rules ['yearlyString'] .= $row ['leave_rule_id'] . ",";
					}
				}
				
				mysqli_free_result ( $result );
				$this->_set ( 'leaveRules', $rules );
			}
		} else {
			return false;
		}
	}
	public function _setGeneralPayParams() {
		if ($this->isHr || $this->isEmployee) {
			if ($this->_get ( 'generalPayParams' ) == '') {
				$this->database = new Database ();
				$stmt = "SELECT ps.pay_structure_id, ps.display_name,ps.alias_name,ps.type
FROM company_pay_structure ps WHERE type IN ('A','D') AND ps.display_flag='1' ORDER BY ps.type , ps.sort_order";
				$result = mysqli_query ( $this->database->getConnection (), $stmt );
				$payParams = array (
						"A" => array (),
						"D" => array (),
						"allowString" => "",
						"deduString" => "" 
				);
				while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
					if ($row ['type'] == "A") {
						$payParams ["A"] [] = $row;
						$payParams ['allowString'] .= $row ['pay_structure_id'] . ",";
					} elseif ($row ['type'] == "D") {
						$payParams ["D"] [] = $row;
						$payParams ['deduString'] .= $row ['pay_structure_id'] . ",";
					} else {
					}
				}
				
				mysqli_free_result ( $result );
				$this->_set ( 'generalPayParams', $payParams );
			}
		} else {
			return false;
		}
	}
	public function _setMiscPayParams() {
		if ($this->isHr || $this->isEmployee) {
			if ($this->_get ( 'miscPayParams' ) == '') {
				$this->database = new Database ();
				$stmt = "SELECT ps.pay_structure_id, ps.display_name,ps.alias_name,ps.type
FROM company_pay_structure ps WHERE type IN ('MP','MD') AND ps.display_flag='1' ORDER BY ps.type , ps.sort_order";
				$result = mysqli_query ( $this->database->getConnection (), $stmt );
				$payParams = array (
						"MP" => array (),
						"MD" => array (),
						"miscAllowString" => "",
						"miscDeduString" => "" 
				);
				while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
					if ($row ['type'] == "MP") {
						$payParams ["MP"] [] = $row;
						$payParams ['miscAllowString'] .= $row ['pay_structure_id'] . ",";
					} elseif ($row ['type'] == "MD") {
						$payParams ["MD"] [] = $row;
						$payParams ['miscDeduString'] .= $row ['pay_structure_id'] . ",";
					} else {
					}
				}
				mysqli_free_result ( $result );
				$this->_set ( 'miscPayParams', $payParams );
			}
		} else {
			return false;
		}
	}
	public function _setRetirementParams() {
		if ($this->isHr || $this->isEmployee) {
			if ($this->_get ( 'retirementParams' ) == '') {
				$this->database = new Database ();
				$stmt = "SELECT ps.pay_structure_id, ps.display_name,ps.alias_name,ps.type
FROM company_pay_structure ps WHERE type IN ('RA','RD') AND ps.display_flag='1' ORDER BY ps.type , ps.sort_order";
				$result = mysqli_query ( $this->database->getConnection (), $stmt );
				$payParams = array (
						"RA" => array (),
						"RD" => array (),
						"retirementAllowString" => "",
						"retirementDeduString" => "" 
				);
				while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
					if ($row ['type'] == "RA") {
						$payParams ["RA"] [] = $row;
						$payParams ['retirementAllowString'] .= $row ['pay_structure_id'] . ",";
					} elseif ($row ['type'] == "RD") {
						$payParams ["RD"] [] = $row;
						$payParams ['retirementDeduString'] .= $row ['pay_structure_id'] . ",";
					}
				}
				mysqli_free_result ( $result );
				$this->_set ( 'retirementParams', $payParams );
			}
		} else {
			return false;
		}
	}
public function convert_number_to_words($number) {
		  $no = floor ( $number );
		  $point = round ( $number - $no, 2 ) * 100;
		  $hundred = null;
		  $digits_1 = strlen ( $no );
		  $i = 0;
		  $str = array ();
		  $words = array (
			'0' => '',
			'1' => 'one',
			'2' => 'two',
			'3' => 'three',
			'4' => 'four',
			'5' => 'five',
			'6' => 'six',
			'7' => 'seven',
			'8' => 'eight',
			'9' => 'nine',
			'10' => 'ten',
			'11' => 'eleven',
			'12' => 'twelve',
			'13' => 'thirteen',
			'14' => 'fourteen',
			'15' => 'fifteen',
			'16' => 'sixteen',
			'17' => 'seventeen',
			'18' => 'eighteen',
			'19' => 'nineteen',
			'20' => 'twenty',
			'30' => 'thirty',
			'40' => 'forty',
			'50' => 'fifty',
			'60' => 'sixty',
			'70' => 'seventy',
			'80' => 'eighty',
			'90' => 'ninety'
		  );
		  $digits = array (
			'',
			'hundred',
			'thousand',
			'lakh',
			'crore'
		  );
		  while ( $i < $digits_1 ) {
		   $divider = ($i == 2) ? 10 : 100;
		   $number = floor ( $no % $divider );
		   $no = floor ( $no / $divider );
		   $i += ($divider == 10) ? 1 : 2;
		   if ($number) {
			$plural = (($counter = count ( $str )) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str [0]) ? ' ' : null;
			$str [] = ($number < 21) ? $words [$number] . " " . $digits [$counter] . $plural . " " . $hundred : $words [floor ( $number / 10 ) * 10] . " " . $words [$number % 10] . " " . $digits [$counter] . $plural . " " . $hundred;
		   } else
			$str [] = null;
		  }
		  $str = array_reverse ( $str );
		  $result = implode ( '', $str );
		  $points =  $point ? "rupees and ".self::convert_number_to_words($point,1) . "  paise ": '';
		  return $result . $points;
       }
	
}
?>