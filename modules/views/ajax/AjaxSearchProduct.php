<?php

include '../../common/top.php';
$word = trim($_REQUEST['word']);
$selected=explode(',',$_REQUEST['selected']);
$wordForMo = end(explode('.', $word));
$id=$_REQUEST['id'];
    $query = $fpdo->from('products')
           
            ->where(" id !='$id' and LOWER(`title`) like LOWER('%$wordForMo%') ")->fetchAll();
    
  
   
if ($query) {
    echo "<table class='table table-bordered '>";
 
    echo "<tbody>";
    foreach ($query as $item) {
        
       if (in_array($item['id'], $selected)) {
           $clsss='addedFromModal';
       }else{
           $clsss="";
       }
        echo "<tr class='addFromSearchAddons ".$clsss."'  data-id='" . $item['id'] . "'>";
        echo
        $utils->make_tag_html($item['title'], "td") ;


        echo"</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "No Results Found";
}
