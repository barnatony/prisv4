<?php
class Menu extends Model {

	function __construct($menu_id='') {
		parent::__construct('menu_id','navigation_menu'); //primary key = post_id; tablename = blog
		$this->rs['menu_id'] = '';
		$this->rs['menu_name'] = '';
		$this->rs['parent_id'] = '';
		$this->rs['menu_url'] = '';
		$this->rs['menu_hierarchy'] = ' ';
		$this->rs['updated_on'] = '';
		$this->rs['updated_by'] = '';
		if ($menu_id)
			$this->retrieve($menu_id);
	}

	function create() {
		$this->rs['updated_by'] = $_SESSION['authname'];
		$this->rs['updated_on'] =date('Y-m-d H:i:s');
		return parent::create();
	}
	
	
	
	public function save_menu($menu,$root=null) {
		$dbh=getdbh();
		
		foreach ($menu as $key => $menuItem){	
			$menuItem['hierarchys']=$key;
			$stmt =$dbh->prepare("UPDATE navigation_menu SET menu_name = :menu_name ,menu_url = :menu_url , menu_hierarchy = :menu_hierarchy, parent_id = :parent_id WHERE menu_id = :menu_id ");
			$stmt->bindParam('menu_name', $menuItem['name']);
			$stmt->bindParam('menu_url', $menuItem['url']);
			$stmt->bindParam('menu_hierarchy', $menuItem['hierarchys']);
			$stmt->bindParam('parent_id', $menuItem['parent_id']);
			$stmt->bindParam('menu_id', $menuItem['id']);
			
			if(!$stmt->execute())			
				$result = array(false,"rowCount"=>0,"info"=>$stmt->errorInfo()[2]);
			
				if(isset($menuItem['children']))
					if($menuItem['children'] != null)
				self::save_menu($menuItem['children']);
		}
		
			
		$result = array(true,"rowCount"=>$stmt->rowCount(),"info"=>'Menu Saved Successfully');
		return $result;
}

	public function save(){
	
	}
	
	public static function get_menu_items(){
		$dbh=getdbh();
		$a_json = array();
		$stmt = $dbh->query("SELECT nav.menu_id ,nav.parent_id, nav.menu_name ,nav.menu_url, pNav.menu_id parent_menu_id ,pNav.menu_name parent_menu_name, nav.menu_hierarchy
												FROM navigation_menu nav
												LEFT JOIN navigation_menu pNav
												ON nav.parent_id = pNav.menu_id
												ORDER BY nav.menu_hierarchy ");
		
		
		while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$a_json[]=$rs;
			
			
		};
		
	
		
		function constructMenu($menuItems,$root=null) {
			foreach ( $menuItems as $key => $menuItem ) {
				if ($menuItem['parent_menu_id'] == $root['menu_id']) {
					unset ( $menuItems [$key] ); // remove the item from the search menuItems to perform faster search
					$ch[]= array("name" => $menuItem['menu_name'],
					"menu_hierarchy" => $menuItem['menu_hierarchy'],		
					"menu_id" => $menuItem['menu_id'],	
					"parent_id" => $menuItem['parent_id'],		
					"url" => $menuItem['menu_url'],
					"root" => ($menuItem['parent_menu_id']==""?TRUE:FALSE),
					"children" => constructMenu ($menuItems,$menuItem )
					);
				}
			}
			return empty($ch) ? null : $ch;
		}
		$menuItems = array ();
		$menuItems  = constructMenu ($a_json );
		return $menuItems;
	}
}