<?php

 
class Ecommerce_System extends utils {
   
    
    function addToCart($param,$ajax_path) {
        //print_r($param);
        
        $data=json_encode($param);
        echo "<script>
                $.ajax({
                        type: 'POST',
                        url:'$ajax_path',
                        data: $data,
                        success: function(html){
                                 alert(html);
                        }
                    });
                </script>"; 
        
    }
    
    function removeFromCart($pro_id,$ajax_path) {
        echo "<script>
                $.ajax({
                        type: 'POST',
                        url:'$ajax_path',
                        data: {'pro_id':$pro_id},
                        success: function(html){
                                 alert(html);
                        }
                    });
                </script>"; 
        
    }
    
    // $param is array contain pro_id and new qty
    function changeQTY($param,$ajax_path) {
        $data=json_encode($param);
        echo "<script>
                $.ajax({
                        type: 'POST',
                        url:'$ajax_path',
                        data: $data,
                        success: function(html){
                                 alert(html);
                        }
                    });
                </script>"; 
        
    }
    
    //$param is array contain id value example: $param=array('compare_id'=>1);
    function addToCompare($param,$ajax_path) {
        $data=json_encode($param);
        echo "<script>
                $.ajax({
                        type: 'POST',
                        url:'$ajax_path',
                        data: $data,
                        success: function(html){
                                 alert(html);
                        }
                    });
                </script>"; 
        
    }
    
    //$param is array contain id value example: $param=array('compare_id'=>1);
    function removeFromCompare($param,$ajax_path) {
        $data=json_encode($param);
        echo "<script>
                $.ajax({
                        type: 'POST',
                        url:'$ajax_path',
                        data: $data,
                        success: function(html){
                                 alert(html);
                        }
                    });
                </script>"; 
        
    }
    
  
}

?>
