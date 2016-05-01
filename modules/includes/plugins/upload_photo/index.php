<? include_once("uppo_header.php")?>
<link rel="stylesheet" type="text/css" href="../includes/upload_photo/script/uploadify.css">
<style type="text/css">
body {font: 13px Arial, Helvetica, Sans-serif;}
</style>
<div style="padding:10px;">
<form><div id="queue"></div><input id="file_upload" name="file_upload" type="file" multiple="true"></form>
</div>
<script type="text/javascript">
	<?php $timestamp = time();?>
	$(function() {
		$('#file_upload').uploadify({
			'formData'     : {
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'buttonText':'<?=Upload_photo?>',
			'buttonClass':'uploadify-button',
			'width':'700',
			'swf'      : '../includes/upload_photo/script/uploadify.swf',
			'uploader' : '../includes/upload_photo/script/uploadify.php',
			'onUploadSuccess' : function(file, data, response) {
				//vFiles//alert('The file ' + file.name + 'response of ' + response + ':' + data);				
				showphoto(data);
			},
			'onQueueComplete': function(){				
				//$("#upload_div").hide('slow');
				
			}	

		});
	});
</script>
