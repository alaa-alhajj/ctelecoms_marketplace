<?php 

include "../../common/top_ajax.php";
$table = $_POST['ot'];
$filed = $_POST['of'];
$order_id = $_POST['oi'];
$ids = $_POST['ids'];
$changes=explode("|",$ids);
$old_oders=array();
for($i=0;$i<count($changes)-1;$i++){
	$idsFT=explode(",",$changes[$i]);
	$old_ord=$idsFT[1];	
	
         $sql=$fpdo->from($table)->select($filed)->where($order_id,$old_ord)->fetch();        	
	
        
        $old_oders[$i]=$sql[$filed];
}
for($i=0;$i<count($changes)-1;$i++){
	$idsFT=explode(",",$changes[$i]);
	$id_from=$idsFT[1];
	$id_to=$idsFT[0];
        $qq=$fpdo->update($table)->set( array($filed => $old_oders[$i]))->where($order_id,$id_to);
        $qq->execute();
        
                echo $qq->getQuery()."\n";
        print_r( $qq->getParameters())."\n";
        
        
		
}
?>