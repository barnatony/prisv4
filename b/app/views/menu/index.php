 <div class="content-wrapper">
 <section class="content-header"> 
      <h1>
       Menu
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo myUrl('')?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Menu</li>
      </ol>
    </section>
    
       <!-- Main content -->
    <section class="content" >
     <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Menu</h3>
         
        </div>
        <div class="col-md-12">
        <div class="notification text-center"></div> 
        <div class="col-md-4">
        
             <div class="box box-info" id="create_menu" >
         <div class="box-header with-border">
          <h3 class="box-title">Create Menu</h3>
        </div>
        
        <section class="content" style="min-height: 231px">
     <form id="add_menu" class="add_menu" method="post" >
     <div class="notifications text-center"></div>
     <div class="form-group">
           <label for="menu_name">Menu Name</label>
          <input type="text" class="form-control" id="menu_name" name="menu_name" required placeholder="Enter Menu Name" >
      </div>
      <div class="form-group">
           <label for="menu_url">Menu Url</label>
          <input type="text" class="form-control" id="menu_url" name="menu_url" required placeholder="Enter Menu Url" >
      </div>
      
      <div class="box-footer">
			  
                <button type="button" class="btn btn-danger pull-right" id="cancel" style="margin-right: 15px;">Cancel</button>
                <button type="submit" class="btn btn-info pull-right" id="submit" style="margin-right: 15px;">Create Menu</button>
      </div>
     </form>
     </section>
        
        </div>
        </div>
        <div class="col-md-8">
       
        <ol class="sortable">
<?php 

foreach ($menus as $menu){
	
	
	echo '<li id="list_'.$menu['menu_id'].'" data-id="'.$menu['menu_id'].'" data-hierarchys="'.$menu['menu_id'].'" data-parent_id="'.$menu['parent_id'].'" data-url="'.$menu['url'].'" data-name="'.$menu['name'].'" class="lis"><div id="lists_'.$menu['menu_id'].'"><div class="menu-item-bar"><div class="menu-item-handle ui-sortable-handle"><span class="item-title"><span class="menu-item-title w_show">'.$menu['name'].'</span>
        		<span class="pull-right">
		<i id="sortable_edit" data-n="'.$menu['menu_id'].'" data-id="'.$menu['name'].'" class="fa fa-chevron-down edit_show" title="Edit " style="cursor: pointer;"></i>
    	<i id="sortable_show" data-n="'.$menu['menu_id'].'" data-id="'.$menu['name'].'" class="fa fa-chevron-up up_show" title="Ok " style="cursor: pointer;display:none"></i>
		</span>
        		</span>
        		
        		</div>';

	echo '<div class="menu-item-handle value_column" style="display:none;background:#fff;">
	
        		<div class="form-group">
        		<label style="margin-bottom:12px">Navigation Label</label>
<input type="text" name="navigation_label_edit" id="navigation_label_edit" class="form-control navigation_label_edit" value=""  placeholder="Navigation Label" />
	
        		</div>
        		<div class="form-group">
        		<label style="margin-bottom:12px">Navigation Url</label>
<input type="text" name="navigation_label_url" value="'.$menu['url'].'" class="form-control navigation_label_url" placeholder="Navigation Url"  />
	<div style="margin-top:13px">
				<a data-id="'.$menu['name'].'" class="remove" style="cursor:pointer;color:red">Remove</a> | 
				<a data-id="'.$menu['name'].'" class="valued_times" style="cursor:pointer">Cancel</a>
        </div>		</div>
        		</div>
	
		</div></div>';
	if($menu['children'] != null){
		
		echo '<ol>';
		foreach ($menu['children'] as $children){
			
			echo '<li id="list_'.$children['menu_id'].'" data-id="'.$children['menu_id'].'" data-hierarchys="'.$children['menu_id'].'" data-parent_id="'.$children['parent_id'].'" data-url="'.$children['url'].'" data-name="'.$children['name'].'" class="lis"><div id="lists_'.$children['menu_id'].'"><div class="menu-item-bar"><div class="menu-item-handle ui-sortable-handle"><span class="item-title"><span class="menu-item-title w_show">'.$children['name'].'</span>
		<span class="pull-right">
		<i id="sortable_edit" data-n="'.$children['menu_id'].'" data-id="'.$children['name'].'" class="fa fa-chevron-down edit_show" title="Edit " style="cursor: pointer;"></i>
		<i id="sortable_show" data-n="'.$children['menu_id'].'" data-id="'.$children['name'].'" class="fa fa-chevron-up up_show" title="Ok " style="cursor: pointer;display:none"></i>
			</span>
        		</span>
        		
        		</div>';
			if($children['children'] != NULL){
	echo '<div class="menu-item-handle value_column" style="display:none;background:#fff;">
	
        		<div class="form-group">
        		<label style="margin-bottom:12px">Navigation Label</label>
<input type="text" name="navigation_label_edit" id="navigation_label_edit" class="form-control navigation_label_edit" value=""  placeholder="Navigation Label" />
	
        		</div>
        		<div class="form-group">
        		<label style="margin-bottom:12px">Navigation Url</label>
<input type="text" name="navigation_label_url" value="'.$children['url'].'" class="form-control navigation_label_url" placeholder="Navigation Url"  />
	
        		</div>
        		<div style="margin-top:13px">
				<a data-id="'.$children['name'].'" class="remove" style="cursor:pointer;color:red">Remove</a> | 
				<a data-id="'.$children['name'].'" class="valued_times" style="cursor:pointer">Cancel</a>
        </div>	
		</div></div></div>';	
	
	echo '<ol>';
	foreach ($children['children'] as $children){
		echo '<li id="list_'.$children['menu_id'].'" data-id="'.$children['menu_id'].'" data-hierarchys="'.$children['menu_id'].'" data-parent_id="'.$children['parent_id'].'" data-url="'.$children['url'].'" data-name="'.$children['name'].'" ><div id="lists_'.$children['menu_id'].'"><div class="menu-item-bar"><div class="menu-item-handle ui-sortable-handle"><span class="item-title"><span class="menu-item-title w_show">'.$children['name'].'</span>
		<span class="pull-right">
		<i id="sortable_edit" data-n="'.$children['menu_id'].'" data-id="'.$children['name'].'" class="fa fa-chevron-down edit_show" title="Edit " style="cursor: pointer;"></i>
		<i id="sortable_show" data-n="'.$children['menu_id'].'" data-id="'.$children['name'].'" class="fa fa-chevron-up up_show" title="Ok " style="cursor: pointer;display:none"></i>
			</span>
        		</span>
	
        		</div>';
	
		echo '<div class="menu-item-handle value_column" style="display:none;background:#fff;">
	
        		<div class="form-group">
        		<label style="margin-bottom:12px">Navigation Label</label>
<input type="text" name="navigation_label_edit" id="navigation_label_edit" class="form-control navigation_label_edit" value=""  placeholder="Navigation Label" />
	
        		</div>
        		<div class="form-group">
        		<label style="margin-bottom:12px">Navigation Url</label>
<input type="text" name="navigation_label_url" value="'.$children['url'].'" class="form-control navigation_label_url" placeholder="Navigation Url"  />
	
        		</div>
        		<div style="margin-top:13px">
				<a data-id="'.$children['name'].'" class="remove" style="cursor:pointer;color:red">Remove</a> |
				<a data-id="'.$children['name'].'" class="valued_times" style="cursor:pointer">Cancel</a>
        </div>
		</div></div>';
	}
	
	echo '</li></ol></li>';
	}else{
		echo '</li>';
	}
		}
		
		echo '</li></ol></li>';
	}else{
		echo '</li>';
	}	
}
?>
</ol>
        
        
        </div>
        
        </div>
   
      
<div class="box-footer">
                <button type="button" class="btn btn-danger pull-right">Cancel</button>
                <button type="button" id="save_menu" class="btn btn-info pull-right" style="margin-right: 15px;">Save Menu</button>
              </div>
    </div>
    </section>
    
    </div>
    