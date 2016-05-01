<? include_once("uppo_header.php")?>
<link rel="stylesheet" type="text/css" href="../includes/upload_photo/script/uploadify2.css">
<style type="text/css">
body {font: 13px Arial, Helvetica, Sans-serif;}
</style>
<div style="padding:10px;">
<form><div id="queue"></div><input id="file_upload" name="file_upload" type="file" multiple="false"></form>
</div>
<script type="text/javascript">
	<?php $timestamp = time();?>
	$(function() {
		$('#file_upload').uploadify({
			'multi'    : false,
			'formData'     : {
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'buttonText':'<?=upload_attach?>',
			'width':'150',
			'swf'      : '../includes/upload_photo/script/uploadify.swf',
			'uploader' : '../includes/upload_photo/script/upAttach.php',
			'uploadLimit' : 91,
			'onSelect' : function(file) {
            	$('.uploadify-button').hide();
       		},
			'onUploadSuccess' : function(file, data, response) {							
				saveAttach(data);
			},
			'onQueueComplete': function(){				
				//finshUpload();				
			}

		});
	});
</script>