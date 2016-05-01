<?php
$dbHostname = DATABASE_URL;
$dbUsername = DATABASE_USER;
$dbPassword = DATABASE_PASS;
$dbName     = DATABASE_NAME; 

//  Make connection to database 
//  If no connection made, display error Message   
      
$dblink = MYSQL_CONNECT($dbHostname, $dbUsername, $dbPassword) OR DIE("Error !! Unable to connect to database");       
// Select the database name to be used or else print error message if unsuccessful*/
@mysql_select_db($dbName) or die( "Unable to select database ".$dbName); 
mysql_query("SET NAMES 'utf8'", $dblink); 
?>
