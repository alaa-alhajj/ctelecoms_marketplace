 $(document).ready(function() {    $('a.menu_inner').click(function(){
        close2();
         $id=$(this).attr('data-id');  
       $('.bhg .grid0top').removeClass('animated');
       $('.bhg .grid0top').removeClass('fadeInUp');
       //$('.bhg .grid0top').addClass('animated');
       //$('.bhg .grid0top').addClass('slideOutUp');
        setTimeout(function(){ $('#Details_'+$id).removeClass('displayNone');
        $('#Details_'+$id).removeClass('displayNone');
        
        $('#Details_'+$id).addClass('animated');
        $('#Details_'+$id).addClass('slideInRight');
        $('#Details_'+$id).addClass('current');
        $('.li_'+$id).addClass('active');
        $('.Menu_'+$id).addClass('active');
       // $('.bhg .grid0top').removeClass('animated');
      // $('.bhg .grid0top').removeClass('slideOutUp');
       //$('.bhg .grid0top').addClass('animated');
       //$('.bhg .grid0top').addClass('slideInDown');
            },200);
            
       
        
    
     });
      $('a.menuDetails').click(function(){      
	  $('body').addClass('noscroll');
          $('.zom').addClass('zoomout1');
      $('.bgg').removeClass('displayNone');
      $('.bgg').addClass('animated');
      $('.bgg').addClass('fadeInUp');
      $('.bhg').removeClass('displayNone');
      $('.bhg .grid0top').addClass('animated');
      $('.bhg .grid0top').addClass('fadeInUp');
      
      
    
        $id=$(this).attr('data-id');       
        $('#Details_'+$id).removeClass('displayNone');
        $('#Details_'+$id).addClass('animated');
        $('#Details_'+$id).addClass('fadeInUp');
        $('#Details_'+$id).addClass('current');
         $('.li_'+$id).addClass('active');
         $('.Menu_'+$id).addClass('active');
       
     });
     
     
     $('a.close2').click(function(){
        
      close3();
       $('.bgg').addClass('displayNone');
       $('.bhg').addClass('displayNone');
       
       $('.bgg').removeClass('animated');
       $('.bgg').removeClass('fadeInUp');
       
       $('body').removeClass('noscroll');
       $('.bhg .grid0top').removeClass('animated');
       $('.bhg .grid0top').removeClass('fadeInUp');
   });
     function emptyCash()
     {
         $('.current').addClass('displayNone');
                
                $('.current').removeClass('fadeInUp');
                $('.current').removeClass('slideInRight');
                $('.current').removeClass('slideOutLeft');
                $('.current').removeClass('slideOutDown');
                $('.current').removeClass('animated');
                $('.current').removeClass('current');
         
         
     }
     function close2()
     {
           
           
            $('.current').removeClass('slideInRight');
            $('.current').removeClass('fadeInUp');
            $('.current').addClass('slideOutLeft');
            setTimeout(function(){emptyCash();
            },200);
            $('.li_inner').removeClass('active');
            $('.menu_inner').removeClass('active');
            $('.bhg .grid0top').removeClass('animated');
            $('.bhg .grid0top').removeClass('fadeInUp');
        
        
        
        

     }
     function close3()
     {
           
           $('.zom').removeClass('zoomout1');
		   
            $('.current').removeClass('slideInRight');
            $('.current').removeClass('fadeInUp');
            $('.current').addClass('slideOutDown');
            setTimeout(function(){emptyCash();
            },200);
            $('.li_inner').removeClass('active');
            $('.menu_inner').removeClass('active');
            $('.bhg .grid0top').removeClass('animated');
            $('.bhg .grid0top').removeClass('fadeInUp');
        
        
        
        

     }
    $('#sup').click(function(){
        $('#SignInBlock').toggle();        
        $('#SignUpBlock').toggle();
    });
     $('#sin').click(function(){
        $('#SignInBlock').removeClass('displayNone');
        $('#SignUpBlock').removeClass('displayBlock');
        $('#SignUpBlock').toggle();        
        $('#SignInBlock').toggle();
    });
    
 });        
 $(document).ready(function() {$("#owl-partners").owlCarousel({autoPlay: 3000,navigation : false,navigationText:['',''],items : 4,itemsDesktop : [1199,3],itemsDesktopSmall : [979,3]});});        
 $(document).ready(function(){
     

     
     
     
                $('.menu_tab a').hover(function(){
                    
                $('.menu_tab a').removeClass('active');
                $(this).addClass('active');
                $id=$(this).attr('data-sub');
                
                $('.menu_sub_sub a').removeClass('active2');
                $('.menu_sub_sub a.c'+$id).addClass('active2');
                
                
            });
            
            });
            $('.slider').load(function(){});
            
              $(document).ready(function(){
                      
                       
                       $('.login').click(function(){$('.sign_in').toggle();});
                       var close=true;
                       $('.btn_search').click(function(){
                           
                           $('.search_box').toggle();
                           if(close===true){
                               $(this).addClass('btn-search_active');
                               close=false;
                           }
                           else{
                               $(this).removeClass('btn-search_active');
                               close=true;
                           }
                           
                       });
                });
                $(document).ready(function() {$("#owl-demo").owlCarousel({autoPlay: 3000,navigation : true,navigationText:['',''],items : 4,itemsDesktop : [1199,3],itemsDesktopSmall : [979,3]});});
      
$('#subs').click(function(){
	$email=$('#email').val();

	
		
		$.ajax({
			url : "<?=_PREF?>widgets/footer/subcribe.php?email="+$email,
			type : "POST",
			data :{},
			success : function(msg){
				$('.modal-body').html(msg);
				$('#myModal').modal('toggle');
				document.getElementById("email").value="";

				
			}
		});
	
	
	});
        
                  
        $(function() {
			function openAlert(message,title)
{
$('#Alert_Body').html('');
$('#title_alert').html('');
$('#title_alert').html(title);
$('#Alert_Body').html(message);
$('#AlertModal').modal('show');
}
		var counter=0;var attachments=[];
                    $('#attach_upload').uploadifive({
				'auto'         : true,
				'formData'     : {'photo' : 'something'},
				'queueID'      : 'queue1',
				'uploadScript' : _PREF+"widgets/UploadPhotoAttachment/uploadifive.php",
				'removeCompleted' : false,
				'multi':true,     
                                'onCancel':function(){
                                
                                }
                                ,
				'onUploadComplete' : function(file, data) {
                                   
				if(data!=='False')
				{
				   $('#uploadifive-attach_upload-file-'+counter).attr('data-photo',data);
                                   attachments.push(data);
                                   $('#attach').val(attachments);
				}
                                 counter++;
				}
			});
                   $('#addTicketBtn').click(function(){
                      
                     var user_id=$('#user_id').val();
                     
                     var project_id=$('#project_id').val();
                     var subject=$('#subject').val();
					 var priority_id=$('#priority_id').val();
                     var text=$('#text').val();                     
                     var attach=$('#attach').val();
                     $.ajax({url:_PREF+"addTicket",
                     dataType:'json',
                     type:'post',
                     data:{user_id:user_id,project_id:project_id,subject:subject,text:text,attach:attach,priority_id:priority_id},
                     success:function(data){    
                     $('#addTicket').modal('hide');    
                     openAlert(data[2]);   
                     if(data[0]===1){
                     document.location='';
                 }
                      
                     }
                     });
                     
                     
                   });
                   
                   $('#closeTicketBtn').click(function(){
                      
                    var ticket_id=$('#ticket_id').val();
                     
                     var rank_sa=$('#rank_sa').val();
                     var rank_rs=$('#rank_rs').val();
                     var rank_es=$('#rank_es').val();                     
                     
                     $.ajax({url:_PREF+"CloseTicket",
                     dataType:'json',
                     type:'post',
                     data:{ticket_id:ticket_id,rank_sa:rank_sa,rank_rs:rank_rs,rank_es:rank_es},
                     success:function(data){    
                     $('#CloseTicket').modal('hide');    
                    
                     if(data[0]===1){
                     document.location='';
                 }                      
                     }
                     });
                     
                     
                   });
                   
                   $('#addReplayBtn').click(function(){
                      
                     var user_id=$('#user_id').val();                     
                     var ticket_id=$('#ticket_id').val();
                     var replay=$('#replay').val();
                    
                     $.ajax({url:_PREF+"addReplay",
                     dataType:'json',
                     type:'post',
                     data:{user_id:user_id,ticket_id:ticket_id,replay:replay},
                     success:function(data){    
                     $('#addReplay').modal('hide');    
                     openAlert(data[2]);                     
                      if(data[0]===1){
                     document.location='';
                     }
                      
                     }
                     });
                     
                     
                   });
                   $(document).ready(function() {$("#owl-demo2").owlCarousel({autoPlay: false,navigation : false,navigationText:['',''],items : 1,itemsDesktop : [1199,1],itemsDesktopSmall : [979,1]});});
                    
                });
  $(document).ready(function(){
                $('.menu_tab a').hover(function(){
                    
                $('.menu_tab a').removeClass('active');
                $(this).addClass('active');
                $id=$(this).attr('data-id');
                
                $('.menu_sub_sub a').removeClass('active2');
                $('.menu_sub_sub a.c'+$id).addClass('active2');
                
                
            });
            
            });
            

function initialize() {
  var myLatlng = new google.maps.LatLng($('#lat').val(),$('#lng').val());
  var mapOptions = {
    zoom: 8,
    center: myLatlng,
    navigationControl: false,
    mapTypeControl: false,
    scaleControl: false,    
    zoomControl:false,
    streetViewControl:false,
    rotateControl:false,
    panControl:false,scrollwheel:false, mapTypeId: google.maps.MapTypeId.TERRAIN,
    styles:gray
  };
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map
    
  });
}

google.maps.event.addDomListener(window, 'load', initialize);

 