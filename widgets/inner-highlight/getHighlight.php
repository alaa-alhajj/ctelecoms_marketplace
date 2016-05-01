<?php
$page_id=$_REQUEST['id'];
$query = $this->fpdo->from('inner_highlight')->fetchAll();
$numrows = count($query);
$imgIds=array();

foreach ($query as $row){
		
       $photo = $row['photos']; 
        $imgIds[]=$photo;
}
//print_r($imgIds);
$randomKey = array_rand($imgIds);
$img_id = $imgIds[$randomKey];

$bg = $this->viewPhoto($img_id,'crop',1300,260,'css',1,$_SESSION['dots'],0);
//get page title
$page_info=$this->fpdo->from('cms_pages')->where('id='.$page_id)->fetch();
$page_title=$page_info['title'];
?>

<div class="page-title-sect container-fluid" style="<?=$bg?>">
        <div class="page-title container">
            <div><h1> <?=$page_title?>  </h1></div>
        </div>
</div>