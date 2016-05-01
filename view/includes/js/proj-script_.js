/****************** Execution function *******************/
var i = setInterval("changeHigh(0)",3000);

$(document).ready(function(){
    getGalleriesData(0); //get galleries data 
});
 
/*********************************************************/



function control_SearchBox(){
    if($('.search-form').css('display')==='none'){
        $('.search-form').fadeIn(500);
    }else{
        $('.search-form').fadeOut(500);
    }
}

function showServicesDescription(id){
    $('.services-sect .services-details .srv-btn').removeClass('active');
    $('.services-sect .services-details .srv-btn'+id).addClass('active');  
 
    $('.services-sect .services-details .services-desc .srv-desc').removeClass('active');
    $('.services-sect .services-details .services-desc .srv-desc'+id).addClass('active');    
}

function showServicesSect(id){
    $('.services-sect .services .services-tabs .tab').removeClass('active');
    $('.services-sect .services .services-tabs .tab'+id).addClass('active');  
 
    $('.services-sect .services-details .service-details').removeClass('active');
    $('.services-sect .services-details .service-details'+id).addClass('active');    
}

function highProcess(high_id,prev_high){
    
	$('.officiel-news .news-highlight-sect .news-dtls'+prev_high).velocity('transition.slideLeftOut',800);
	setTimeout("$('.officiel-news .news-highlight-sect .news-dtls"+high_id+"').velocity('transition.slideRightIn',800)",800);
	$('.officiel-news .news-highlight-sect .nav-bar .nav').removeClass('active');
	$('.officiel-news .news-highlight-sect .nav-bar .nav'+high_id).addClass('active');
       
        
}

function changeHigh(high_id){
    var max_high_items=$('.max-high-items').val();
    var curr_high_item=$('.curr-high-item').val();
    var prev_high_item=curr_high_item;
    if(high_id != 0){
        
        curr_high_item=high_id;
		if(high_id-1!=0){
			prev_high_item=high_id-1;
		}else{
			prev_high_item=max_high_items;
		}
		highProcess(high_id,prev_high_item);
    }else{
        curr_high_item++;
        if(curr_high_item > max_high_items){ 
            curr_high_item=1;
        }
		
        highProcess(curr_high_item,prev_high_item);        
    }
    clearTimeout(i);
    i=setInterval("changeHigh(0)",6000);
    $('.curr-high-item').attr('value',curr_high_item);    
}

/********************* galuries Sliders *********************/
function showGallery(id,operation){
    if(id==0){
		var curr_item = $('.curr-item').val();
		var max_item = $('.max-item').val();     
		var prev_item = curr_item;
		if(operation=='prev'){	
			curr_item++;
			if(curr_item > max_item){
				curr_item=1;
			}
				   
		}else{
			curr_item--;
			if(curr_item == 0){
				curr_item=max_item;
			}     
		   
		}
		
		 $('.syria-sect .photo-gallery .imgs-gallery'+prev_item).velocity("transition.flipXOut",800);
		 setTimeout("$('.syria-sect .photo-gallery .imgs-gallery"+curr_item+"').velocity('transition.flipXIn',1000)",800);
	
    }else{
        curr_item=id; 
        $('.syria-sect .photo-gallery .imgs-gallery').removeClass('active');
        $('.syria-sect .photo-gallery .imgs-gallery'+curr_item).addClass('active');
        
        $('.syria-sect .photo-gallery .imgs-gallery').fadeOut(500);
        $('.syria-sect .photo-gallery .imgs-gallery'+curr_item).fadeIn(3000);
    }
        
        $('.syria-sect .syria-sect-header .lbl-cls').removeClass('active');
        $('.syria-sect .syria-sect-header .lbl-cls'+curr_item).addClass('active');
	$('.curr-item').attr('value',curr_item);

}

function SendcContactInfo(){
    $(".container-mask").css('display','inline');
    var name=document.getElementById('name').value;
    var email=document.getElementById('email').value;
    var company=document.getElementById('company').value;
    var message=document.getElementById('message').value;
    
     $.ajax({
        type: "POST",
	url:_PREF+'widgets/footer/sendcontactInfo.php',
        data: {
                'name':name,
                'email':email,
                'company':company,
                'message':message
            },
        success: function(html){
               //alert(html);
               $('.loader-section .loader-icon').hide();
               $('.loader-section .txt-div').html(html);
               setTimeout("$('.container-mask').hide();",2000);
               document.getElementById('name').value='';
               document.getElementById('email').value='';
               document.getElementById('company').value='';
               document.getElementById('message').value='';
        }
    });
  
}

/**********Ahmad*********/

$('document').ready(function(){
	$('.blur-img').mouseenter(function(){
		$(this).animo('blur', {duration: 3, amount: 2});
	}).mouseout(function(){
		$(this).animo('focus');
	});
	
});
//--------------------------------------------------
function getGalleriesData(id){
    $(".gallery-mask").css('display','inline-block');
    //set active
    $('.syria-sect .gal-btn').removeClass('active');
    $('.syria-sect .gal-btn'+id).addClass('active');
        
    $.ajax({
        type: "POST",
	url:'widgets/about_syria/getGalleriesData.php',
        data: {
                'id':id
            },
        success: function(html){
		
		   $('.gallery-content').html(html);
		   $('.gallery-mask').hide();
        }
    });
}

