// JavaScript Document
function uploadImage(){
	$.post("../includes/upload_photo/index.php",{},function (data){
		$("#upload_div").html(data);
		orderPhotos();
	})	
}
function uploadVideo(){
	$.post("../includes/upload_photo/video.php",{},function (data){
		$("#upload_video").html(data);
		orderPhotos();
	})	
}
function uploadAttach(){
	$.post("../includes/upload_photo/attach.php",{},function (data){
		$("#upload_attach").html(data);
		orderPhotos();
	})	
}

function saveVideo(data){
	if(data!=''){
		document.location='subjectVideo.php?sub_id='+sub_id+'&add='+data;
	}
}
function saveAttach(data){
	if(data!=''){
		d=data.split('^');
		document.location='subjectAttach.php?sub_id='+sub_id+'&add='+d[0]+'&name='+d[1];
	}
}
//////////////////////////////////////////////////////

function uploadProAttach(url){
	$.post("../includes/upload_photo/attach_pro.php",{url:url},function (data){
		$("#upload_attach").html(data);
		orderPhotos();
	})	
}
function saveProAttach(data){
	if(data!=''){
		d=data.split('^');
		if(pro_id!=0){
			document.location='productsAttach.php?pro_id='+pro_id+'&add='+d[0]+'&name='+d[1];
		}else{
			document.location='catalogues.php?add='+d[0]+'&name='+d[1];
		}
	}
}
function uploadProVideo(){
	$.post("../includes/upload_photo/video_pro.php",{},function (data){
		$("#upload_video").html(data);
		orderPhotos();
	})	
}
function saveProVideo(data){
	if(data!=''){
		document.location='productsVideo.php?pro_id='+pro_id+'&add='+data;
	}
}
//////////////////////////////////////////////////////
function finshUpload(){
	document.location.reload(true);}
function showphoto(photo){	
	addToText(photo);
	var car_id=parseInt(Math.random()*100000);
	var imges=$("#vFiles").html();
	var pn=photo.split('.');
	var pn_s=pn[0]+'_s.'+pn[1];
	$("#vFiles").html(imges+'<div name="'+pn[0]+'.'+pn[1]+'" id="id_'+car_id+'" style="background-image:url(../../uploads/temp/'+pn_s+')" class="upImage" ><div class="close_butt" onclick="del_photo(\''+car_id+'\',\''+photo+'\')"></div></div>');
}
function addToText(photo){
	str=$('#photosArray').val();
	if(str!=''){
		str+=',';
	}
	str+=photo;
	$('#photosArray').val(str);
	
}
function del_photo(id,photo){
	$('#id_'+id).hide(1000);
	var str=$('#photosArray').val();
	var newVal='';
	var photos=str.split(',');
	var first=0;
	for(i=0;i<photos.length;i++){		
		if(photos[i]!=photo){
			if(first!=0){
				newVal+=',';
			}
			first=1;
			newVal+=photos[i];
		}
	}
	$('#photosArray').val(newVal);
}
function orderPhotos(){
	$("#vFiles").hover(function(){
		$(this).css( "cursor", "move" );
	});		
	$("#vFiles").sortable({
		connectWith: "div",
		cursor: "move",
		forcePlaceholderSize: true,
		opacity: 1,
		stop: function(event, ui){
			var orderChanges="";
			var sortorder="";
			$("#vFiles div[class=upImage]").each(function(){
				if($(this).css('display') != 'none'){
					var columnId=$(this).attr("name");
					orderChanges+=columnId+",";				
				}
			});
			var orderChanges=orderChanges.substr(0,orderChanges.length-1);
			$('#photosArray').val(orderChanges);
		}
	})
}
//-----------------Photos----------------------------
var ajaxLoaderBasy=0;
var activeFolder=-1;
var loading='<div class="loading fl">'+_loading+' ...</div>';
function loader(s){
	if(s==1){
		ajaxLoaderBasy=1;
		$('#loading').show();
	}else{
		ajaxLoaderBasy=0;
		$('#loading').hide();
	}
}
function openPhotoLibrary(){
	$('.flexWin').show()
	document.body.style.overflow = 'hidden';
	loadThisFolder('all');
}
function closeLwin(){
	$('.flexWin').hide();
	document.body.style.overflow = 'visible';
	orderPhotos();
}
function loadThisFolder(id){
	dir=$('#fol'+id).attr('Folder');
	//if(activeFolder!=id){
		$('#fol'+activeFolder).attr('class', 'folderI');
		$('#fol'+id).attr('class', 'folderI2');
		activeFolder=id;
		$('#l_files').html(loading);
		var selPhotos=$('#photosArray').val();
		if(ajaxLoaderBasy==0){
			$.post("../includes/upload_photo/showFolderPhotos.php",{dir:dir,ph:selPhotos},function(data){
				$('#l_files').html(data);
			});
		}
	//}
}
function addTolist(id,photo){
	if($('#'+id).attr('act')==0){		
		$('#'+id).css('background-color','#abe799');		
		$('#'+id).attr('act',1);
		showphotoFromLab(photo,id)
	}
}
function showphotoFromLab(photo,id){	
	addToText(photo);
	var car_id=parseInt(Math.random()*100000);
	$('#'+id).attr('photo_id',car_id);	 
	var imges=$("#vFiles").html();
	var pn_s='up_'+photo;
	$("#vFiles").html(imges+'<div name="'+photo+'" id="id_'+car_id+'" style="background-image:url(../../uploads/cash/'+pn_s+')" class="upImage" ><div class="close_butt" onclick="del_photo(\''+car_id+'\',\''+photo+'\')"></div></div>');
}
//-----------------Attatch----------------------------
function openAttatchLibrary(table){
	$('.flexWin').show()
	document.body.style.overflow = 'hidden';
	loadAttachs(table);
}
function loadAttachs(table){
	$('#l_attach').html(loading);
	var attachs=$('#attachs').val();
	$.post("../includes/upload_photo/showAttach.php",{attachs:attachs,table:table},function(data){
		$('#l_attach').html(data);
	});
}
function addTolist2(id,att){
	if($('#'+id).attr('act')==0){		
		$('#'+id).css('background-color','#abe799');		
		$('#'+id).attr('act',1);
		addToTextAtt(att);
	}else{
		$('#'+id).css('background-color','#fff');		
		$('#'+id).attr('act',0);
		RemoveFromTextAtt(att);
	}
}
function addToTextAtt(att){
	str=$('#attachs').val();
	if(str!=''){str+=',';}str+=att;
	$('#attachs').val(str);	
}
function RemoveFromTextAtt(att){
	var str=$('#attachs').val();
	var newVal='';
	var atts=str.split(',');
	var first=0;
	for(i=0;i<atts.length;i++){		
		if(atts[i]!=att){
			if(first!=0){
				newVal+=',';
			}
			first=1;
			newVal+=atts[i];
		}
	}
	$('#attachs').val(newVal);
}
//-----------------Video----------------------------
function openVideoLibrary(table){
	$('.flexWin').show()
	document.body.style.overflow = 'hidden';
	loadVideos(table);
}
function loadVideos(table){
	$('#l_attach').html(loading);
	var attachs=$('#attachs').val();
	$.post("../includes/upload_photo/showVideos.php",{attachs:attachs,table:table},function(data){
		$('#l_attach').html(data);
	});
}