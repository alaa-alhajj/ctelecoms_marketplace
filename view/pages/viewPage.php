<?php 
    include('../common/header.php');
		$id = $_REQUEST['id'];
		echo "<div class='block'>";
		echo $widgets->getPage($id);
		echo "</div>";
    include('../common/footer.php');
?>