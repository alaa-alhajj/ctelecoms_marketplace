<?php
$word = $_REQUEST['word']='a';
$page_id = $_REQUEST['id']=1;

/************* labels **************/
$search_result='نيجة البحث';
$_pages="الصفحات النصية";
$News="الأخبار";
$Activities="النشاطات";
$Services="الخدمات";
$Banners="الإعلانات";
$discover_syria="إكتشف سورية";
$Total_result="النتيجة الكلية";
$no_result="لا نتيجة";
$_noResult="لم يتم إدخال كلمة بحث.";
/****************************/
$replace=array("or 1=1","OR 1=1","Or 1=1","Drop",";","like '%","like '","LIKE '","drop","DROP","table","TABLE");
$word=  str_replace($replace, '', $word);

?>
<div class='site-title  col-sm-12'><div><h1> <?=$search_result?></h1></div></div>
<?php
$result_row_limit = 50;
$search = 'pages_pages news_news activities_activities banners_banners discover_syria';
$search_arr = explode(' ',$search);
$total_result=0;
$res='';
$res .="<div class='search_result col-sm-12'>
			<TABLE  class='table search'>";
if($word!='')
    {
	$s=stripslashes($word);

	for($ct=0;$ct<count($search_arr);$ct++){
		switch($search_arr[$ct]){
                        case 'pages_pages':	
				//-----------Pages Start-----------------------------------------------------
                                $table_name='pages_pages';
                                $Stmt=" (title LIKE ('%$s%') or details  LIKE ('%$s%')) and lang='$pLang' and active='1' ";
                                $order="id ASC";
                                 
				$module_name = $_pages;
				$id_name = "id";
				$title_name = "title";
				$module_linke = "Page";
		
				//-----------Pages End-------------------------------------------------------
			break;
                    
                        case 'news_news':
				//-----------News Start------------------------------------------------------
                                $table_name='news_news';
                                $Stmt=" (title LIKE ('%$s%') or details  LIKE ('%$s%')) and lang='$pLang' and active='1' ";
                                $order="news_type ASC";
                                 
				$module_name = $News;
				$id_name = "id";
				$title_name = "title";
				$module_linke = "News";
				//-----------News End--------------------------------------------------------
				
			break;
                      case 'banners_banners':
				//-----------Banners Start------------------------------------------------------
                                $table_name='banners_banners';
                                $Stmt=" (title LIKE ('%$s%') or details  LIKE ('%$s%')) and lang='$pLang' and active='1' ";
                                $order="banners_type ASC";
                                 
				$module_name = $Banners;
				$id_name = "id";
				$title_name = "title";
				$module_linke = "Banner";
				//-----------Banners End--------------------------------------------------------
				
			break;
                        case 'activities_activities':
				//-----------Activities Start------------------------------------------------------
                                $table_name='activities_activities';
                                $Stmt=" (title LIKE ('%$s%') or details  LIKE ('%$s%')) and lang='$pLang' and active='1' ";
                                $order="activities_type ASC";
                                
				$module_name = $Activities;
				$id_name = "id";
				$title_name = "title";
				$module_linke = "Activity";
				//-----------Activities End--------------------------------------------------------
			break;
                        case 'services_services':
				//-----------News Start------------------------------------------------------
                                 $table_name='services_services';
                                 $Stmt=" (title LIKE ('%$s%') or details  LIKE ('%$s%')) and lang='$pLang' and active='1'";
                                 $order="id ASC";
                                
				$module_name = $Services;
				$id_name = "id";
				$title_name = "title";
				$module_linke = "Service";
				//-----------News End--------------------------------------------------------
				
			break;
                         case 'discover_syria':
				//-----------discover_syria Start------------------------------------------------------
                                $table_name='discover_syria';
                                $Stmt=" (title LIKE ('%$s%') or details  LIKE ('%$s%')) and active='1' ";
                                $order="ord ASC";
                                 
				$module_name = $discover_syria;
				$id_name = "id";
				$title_name = "title";
				$module_linke = "DiscoverSyria";
				//-----------discover_syria End--------------------------------------------------------
				
			break;
                        case 'projects_projects':
				//-----------News Start------------------------------------------------------
                                $table_name='projects_projects';
                                $Stmt=" (title LIKE ('%$s%') or details  LIKE ('%$s%')) and lang='$pLang' and active='1'";
                                $order="id ASC";
                                
				$module_name = Projects;
				$id_name = "id";
				$title_name = "title";
				$module_linke = "Project";
				//-----------News End--------------------------------------------------------
				
			break;                    
                        case 'products_products':	
				//-----------Pages Start-----------------------------------------------------
                                $table_name='products_products';
                                $Stmt=" (name_$pLang LIKE ('%$s%') or desc_$pLang  LIKE ('%$s%')) and active='1'";
                                $order="id ASC";
                                
                                $module_name = _products;
				$id_name = "id";
				$title_name = "name_".$pLang;
				$module_linke = "Product";
				//-----------Pages End-------------------------------------------------------
			break;
			case 'programs':
				//-----------Programs Start----------------------------------------------------
                                $table_name='programs';
                                $Stmt=" (name_$pLang LIKE ('%$s%') or brief_$pLang  LIKE ('%$s%') or desc_$pLang  LIKE ('%$s%')) and active='1' ";
                                $order="id ASC";
                                
				$module_name = Projects;
				$id_name = "id";
				$title_name = "name_$pLang";
				$module_linke = "Project";
				//-----------Programs End------------------------------------------------------
			break;
                   
                    case 'galleries_galleries':
				//-----------galleries Start--------------------------------------------------
                                $table_name='galleries_galleries';
                                $Stmt=" (name LIKE ('%$s%') or desc  LIKE ('%$s%')  and active='1' and lang='$pLang' ";
                                $order="id ASC";
                                
				$module_name = galleries;
				$id_name = "id";
				$title_name = "name";
				$module_linke = "Galleries";
				//-----------galleries End----------------------------------------------------
			break;                      
			}
                  // this code to print guery       
//                $query = $this->fpdo->from($table_name)->where(" $Stmt ");
//                echo $query->getQuery();
//                echo "<br />";
                
                $query = $this->fpdo->from($table_name)->where(" $Stmt ")->orderBy(" $order ")->fetchAll();
                $numrows = count($query);
                
		if($numrows > 0){

				$res .= "<TR  height='40'><td colspan='2' class='subtitle'><h3>$module_name ($numrows)</h3></td></TR>";	

			if($numrows>0 ){
				$i=0;
				foreach ($query as $row) {
					$id=$row[$id_name];
					$title= $utils->limit($row[$title_name],$result_row_limit);
					$title1 = trim(str_replace(' ','-',$row[$title_name]));
					if($search_arr[$ct]=='faqs_faqs')					{
						$link=_PREF."$pLang/page$page_id/";
					}
					else{$link=_PREF."$pLang/page$page_id/$module_linke$id/$title1-$word";}
					
					if($i%2==0){$class='high_td';}else{$class='';}
					$res .= "<TR>
								<TD class='$class'>
									<a href='$link'>".stripslashes($title)."</a>
								</TD>
								
							</TR>";
					$total_result++;
					
                                        
				}
                              
			}
		}
	}
			
	if($total_result>0){
		$res .= "<h3  dir='"._DIR."'>".$Total_result." ($total_result)</h3>";
	}else{
		$res .= '<tr><p>'.$no_result.'</p></tr>';
	}
}else{
	$res .= '<tr><p>'.$_noResult.'</p></tr>';
}
$res .= "</TABLE></div>";

echo $res;
?>