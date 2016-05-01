<?php
/**
 * Description of voila
 *
 * @author Ahmad mahmoud
 */
 
class path extends utils {
	
    var $page_uri;
    var $file_name;
    var $page_id;

    function __construct() {

        global $fpdo;
        $this->fpdo = & $fpdo;
    }

   
}

?>