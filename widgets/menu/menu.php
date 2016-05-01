<?php
$query = $this->fpdo->from('menus')->where("  p_id=0 and active='1' and lang='".$_SESSION['pLang']."' ")->orderBy("item_order ASC")->fetchAll();
$menu_numrows = count($query);

$i=0;
if($menu_numrows){
	$file;
	if( $uri && $uri != 'index.php' ){
		//$cur_id = getMenuId($file,$pLang);
		//$parent = getTopParent('menus',$pLang, $cur_id);
		if(!$parent){ 
			
			//$parent_id = getMenuId('menus',$parent_url,$pLang); 
			//$parent = getTopParent('menus',$pLang, $parent_id);
		}
	}
	foreach ($query as $row){
		
		$p_id= $row["menu_id"];
		$p_item_name= $row["item_label"];
		$p_item_link= $row["item_link"];

		$item_class = '';
		
		if($i==($menu_numrows-1)){$item_class2='last-item';}else{$item_class2='';}
		if($i==0){$item_class3='first-item';}else{$item_class3='';}
		if($p_item_link == '#'){
			$p_link = '#';
		}else{
			if($p_link){
			$p_link = str_replace($_SESSION['pLang'].$_SESSION['pLang'].'/',$_SESSION['pLang'].'/',_PREF.$_SESSION['pLang'].$p_item_link);}else{$p_link =_PREF;}
			if($p_id == $parent){
				$item_class = "active";
			}else{
				$item_class = "";
			}
		}	
		$class1='';
		$query2 = $this->fpdo->from('menus')->where("  p_id='$p_id' and active='1' ")->orderBy("item_order ASC")->fetchAll();
		$menu_numrows2 = count($query2);
		if($menu_numrows2>0){
			$j=0;
			$res_menu .='<li class=" main-menu-item dropdown '.$item_class.' '.$item_class2.' '.$item_class3.'">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$p_item_name.'<span class="caret"></span></a>  <ul class="dropdown-menu">';
			foreach ($query2 as $row2){
				$sub_id= $row2["menu_id"];
				$sub_item_name= $row2["item_label"];
				$sub_item_link= $row2["item_link"];
				if($college_id!=''){
					$arr_link=  explode('/', $sub_item_link);
					$new_link="";
					$count=0;
					foreach ($arr_link as $li){
						if($count==0){$new_link.="College".$college_id."/";}
						else{$new_link.=$li."/";}
						$count++;
					}
					$new_link=substr($new_link,0,-1);
					 $sub_item_link=_PREF.$_SESSION['pLang']."/".$new_link;   
					
				}else{$sub_item_link=_PREF  .$sub_item_link;}
				 $res_menu .= "<li><a href='$sub_item_link'>$sub_item_name</a></li>";
				 $j++;
			}
			$res_menu .='</ul></li>';
		}else{
			$res_menu .= "<li class='$item_class $item_class2 $item_class3 main-menu-item'><a href='$p_link'>$p_item_name</a></li>";
		}
		$i++;
	}
}


if ($menu_numrows>0) {
	
        
     
	while ($i<$menu_numrows){
	

		
                
					
					
		
		
		$i++;
	}
}


?>

<div class="menu-sect col-sm-8 nopadding">
                <div class="menu col-sm-12 nopadding">            
                    <div class="col-sm-12 nopadding">
                        <!-- Static navbar -->
                        <nav class="navbar nopadding">
                          <div class="container-fluid nopadding">
                            <div class="navbar-header">
                              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                              </button>
                            </div>
                            <div id="navbar" class="navbar-collapse collapse">
                              <ul class="nav navbar-nav">
									<?=$res_menu?> 
                              </ul>
                            </div><!--/.nav-collapse -->
                          </div><!--/.container-fluid -->
                        </nav>
                    </div>
                </div>    
            </div>