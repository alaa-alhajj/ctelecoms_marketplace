<?php

include "../../common/top_ajax.php";
{
    $d_array = $_POST['output_data'];
    $table = $_POST['table'];
    $pid = $_POST['parent'];
    $tid = $_POST['table_id'];
    $ord = $_POST['item_order'];
    $result = json_decode($d_array);
    $i = 1;
    foreach ($result as $var => $value) {
        $update_id = $value->id;
        $valuesArray = array();
        $valuesArray[$pid] = '0';
        $valuesArray[$ord] = $i;
        $fpdo->update($table)->set($valuesArray)->where($tid, $update_id)->execute();
        //  mysql_query("UPDATE $table SET " . $pid . " = 0," . $ord . "='$i' WHERE " . $tid . " =  '$update_id'");
        $i++;

        if (!empty($value->children))
            $j = 1;
        foreach ($value->children as $vchild) {
            $update_id = $vchild->id;
            $parentId = $value->id;
            $valuesArray = array();
            $valuesArray[$pid] = $parentId;
            $valuesArray[$ord] = $j;
            $fpdo->update($table)->set($valuesArray)->where($tid, $update_id)->execute();
            // mysql_query("UPDATE $table SET " . $pid . " ='$parentId'," . $ord . "='$j' WHERE " . $tid . " =  '$update_id'");
            $j++;
            if (!empty($vchild->children))
                foreach ($vchild->children as $vchild1) {
                    $update_id = $vchild1->id;
                    $parentId = $vchild->id;
                    $valuesArray = array();
                    $valuesArray[$pid] = $parentId;
                    $valuesArray[$ord] = $j;
                    $fpdo->update($table)->set($valuesArray)->where($tid, $update_id)->execute();
                    //mysql_query("UPDATE $table SET " . $pid . " ='$parentId'," . $ord . "='$j' WHERE " . $tid . " =  '$update_id'");
                    $j++;

                    if (!empty($vchild1->children))
                        foreach ($vchild1->children as $vchild2) {
                            $update_id = $vchild2->id;
                            $parentId = $vchild1->id;
                            $valuesArray = array();
                            $valuesArray[$pid] = $parentId;
                            $valuesArray[$ord] = $j;
                            $fpdo->update($table)->set($valuesArray)->where($tid, $update_id)->execute();
                            // mysql_query("UPDATE $table SET " . $pid . " ='$parentId'," . $ord . "='$j' WHERE " . $tid . " =  '$update_id'");
                            $j++;

                            if (!empty($vchild2->children))
                                foreach ($vchild2->children as $vchild3) {
                                    $update_id = $vchild3->id;
                                    $parentId = $vchild2->id;
                                    mysql_query("UPDATE $table SET " . $pid . " ='$parentId'," . $ord . "='$j' WHERE " . $tid . " =  '$update_id'");
                                    $j++;
                                    if (!empty($vchild3->children))
                                        foreach ($vchild3->children as $vchild4) {
                                            $update_id = $vchild4->id;
                                            $parentId = $vchild3->id;
                                            $valuesArray = array();
                                            $valuesArray[$pid] = $parentId;
                                            $valuesArray[$ord] = $j;
                                            $fpdo->update($table)->set($valuesArray)->where($tid, $update_id)->execute();
//mysql_query("UPDATE $table SET " . $pid . " ='$parentId'," . $ord . "='$j' WHERE " . $tid . " =  '$update_id'");
                                            $j++;

                                            if (!empty($vchild4->children))
                                                foreach ($vchild4->children as $vchild5) {
                                                    $update_id = $vchild5->id;
                                                    $parentId = $vchild4->id;
                                                    mysql_query("UPDATE $table SET " . $pid . " ='$parentId'," . $ord . "='$j' WHERE " . $tid . " =  '$update_id'");
                                                    $j++;

                                                    if (!empty($vchild5->children))
                                                        foreach ($vchild5->children as $vchild6) {
                                                            $update_id = $vchild6->id;
                                                            $parentId = $vchild5->id;
                                                            $valuesArray = array();
                                                            $valuesArray[$pid] = $parentId;
                                                            $valuesArray[$ord] = $j;
                                                            $fpdo->update($table)->set($valuesArray)->where($tid, $update_id)->execute();
//                                                            mysql_query("UPDATE $table SET " . $pid . " ='$parentId'," . $ord . "='$j' WHERE " . $tid . " =  '$update_id'");
                                                            $j++;
                                                            if (!empty($vchild6->children))
                                                                foreach ($vchild6->children as $vchild7) {
                                                                    $update_id = $vchild7->id;
                                                                    $parentId = $vchild6->id;
                                                                    $valuesArray = array();
                                                                    $valuesArray[$pid] = $parentId;
                                                                    $valuesArray[$ord] = $j;
                                                                    $fpdo->update($table)->set($valuesArray)->where($tid, $update_id)->execute();
                                                                    // mysql_query("UPDATE $table SET " . $pid . " ='$parentId'," . $ord . "='$j' WHERE " . $tid . " =  '$update_id'");
                                                                    $j++;
                                                                    if (!empty($vchild7->children))
                                                                        foreach ($vchild7->children as $vchild8) {
                                                                            $update_id = $vchild8->id;
                                                                            $parentId = $vchild7->id;
                                                                            $valuesArray = array();
                                                                            $valuesArray[$pid] = $parentId;
                                                                            $valuesArray[$ord] = $j;
                                                                            $fpdo->update($table)->set($valuesArray)->where($tid, $update_id)->execute();
                                                                            //mysql_query("UPDATE $table SET " . $pid . " ='$parentId'," . $ord . "='$j' WHERE " . $tid . " =  '$update_id'");
                                                                            $j++;
                                                                        }
                                                                }
                                                        }
                                                }
                                        }
                                }
                        }
                }
        }
    }
}
?>
<?php

//  header( 'Location: http://learnipoint.com/nested/' ) ;
?>