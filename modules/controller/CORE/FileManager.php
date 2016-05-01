<?php

class FileManager {

    function __construct() {
        echo
        '';
    }

    function SelectPhotos($filedName, $values) {

        return $this->Library($filedName, $values, 1);
    }

    function SelectVideos($filedName, $values) {
        return $this->Library($filedName, $values, 3);
    }

    function SelectFiles($filedName, $values) {

        return $this->Library($filedName, $values, 4);
    }

    function Library($filedName, $values, $type) {
        $str = '        
        <script src="../../includes/File_Manager/script.js" ></script>	      
	<script>$(document).ready(function(){loadLibraryAll(\'' . $filedName . '\',\'' . $values . '\',\'' . $type . '\');})</script>
	 <div id="sf_' . $filedName . '"></div>  ';
      
        return $str;
    }

}

?>
