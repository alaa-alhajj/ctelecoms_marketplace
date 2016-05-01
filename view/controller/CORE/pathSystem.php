<?php
/**
 * Description of voila
 *
 * @author Ahmad mahmoud
 */
 
class path extends utils {


	
	
	function getMenuId($uri){
		$menu_row = $this->fpdo->from('menus')->where(" item_link='$uri' ")->fetch();
		$menu_id = $menu_row['menu_id'];
		if($menu_id==''){return 0;}else{return $menu_id;}
	}
	
	function getMenuTitle($menu_id){
		$menu_row = $this->fpdo->from('menus')->where(" menu_id='$menu_id' ")->fetch();
		$item_label = $menu_row['item_label'];
		if($item_label==''){return 'null';}else{return $item_label;}
	}
	
	function getMenuLink($menu_id){
		$menu_row = $this->fpdo->from('menus')->where(" menu_id='$menu_id' ")->fetch();
		$menu_link = $menu_row['item_link'];
		if($menu_link==''){return '#';}else{return $_SESSION['_PREF'].$menu_link;}
	}
	
	function getParentMenuId($menu_id){
		$menu_row = $this->fpdo->from('menus')->where(" menu_id='$menu_id' ")->fetch();
		$parent_id = $menu_row['p_id'];
		if($parent_id==''){return 'null';}else{return $parent_id;}
	}
	
	
	function getPageInfo($page_id){
		$page_row = $this->fpdo->from('cms_pages')->where(" id='$page_id' ")->fetch();
		$page_title = $page_row['title'];
		$module_id = $page_row['module_id'];
		$is_main_page = $page_row['is_main'];
		
		$module_page_row = $this->fpdo->from('cms_pages')->where(" module_id='$module_id' AND is_main='1' ")->fetch();
		$module_page_id = $module_page_row['id'];
		$module_page_title = $module_page_row['title'];
		$module_page_title = $module_page_row['title'];
		
		return array('page_title'=>$page_title,'module_id'=>$module_id,'module_page_title'=>$module_page_title,'module_page_id'=>$module_page_id,'is_main_page'=>$is_main_page);
	}
	
	function getPath($sep='&raquo;'){
		
		$page_uri = trim($_SERVER["REQUEST_URI"],_PREF.$_SESSION['pLang']);
		$page_uri = urldecode($page_uri);
		$page_uri = $_SESSION['pLang'].'/'.$page_uri;
		$page_info = $this->getPageInfo($_REQUEST['id']);
		$module_page_id = $page_info['module_page_id'];
		$module_page_title = $page_info['module_page_title'];
		$module_id = $page_info['module_id'];
		$page_title = $page_info['page_title'];
		$is_main_page = $page_info['is_main_page'];
		$result ="<a  href='".$_SESSION['_PREF']."'>".Home."</a>";
		
        $menu_id = $this->getMenuId($page_uri);
		if($menu_id){
			$curr_page_title = $this->getMenuTitle($menu_id);
			$parent_id = $this->getParentMenuId($menu_id);
			
			while($parent_id!=0){
				$menu_title = $this->getMenuTitle($parent_id);
				$parent_id = $this->getParentMenuId($parent_id);
				$parent_link = $this->getMenuLink($parent_id);
				$result .= "&nbsp;$sep&nbsp;<a href='$parent_link'>$menu_title</a>";
			}
			$result .="&nbsp;$sep&nbsp;<a class='active' href='#'>$curr_page_title</a>";
		}else{
			if($is_main_page){
				$result .="&nbsp;$sep&nbsp;<a class='active' href='#'>$module_page_title</a>";
			}else{
				if($module_page_title){
					$result .= "&nbsp;$sep&nbsp;<a href='".$_SESSION['_PREF'].$_SESSION['pLang']."/page$module_page_id/".str_replace(' ','-',$module_page_title)."'>$module_page_title</a>";
				}
				$result .="&nbsp;$sep&nbsp;<a class='active' href='#'>$page_title</a>";
			}
			
		}
		
        $result = "<div class='path col-sm-12 nopadding'>$result</div>";
		
		return $result;
	}
   
}

?>